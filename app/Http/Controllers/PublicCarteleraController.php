<?php

namespace App\Http\Controllers;

use App\Enums\ContentStatus;
use App\Models\Event;
use App\Models\EventCategory;
use App\Models\EventSchedule;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class PublicCarteleraController extends Controller
{
    public function index(Request $request): View
    {
        $eventsQuery = Event::query()
            ->where('status', ContentStatus::Published->value)
            ->where(fn ($query) => $query
                ->whereNull('publish_starts_at')
                ->orWhere('publish_starts_at', '<=', now()))
            ->where(fn ($query) => $query
                ->whereNull('publish_ends_at')
                ->orWhere('publish_ends_at', '>=', now()))
            ->whereHas('schedules', function ($query): void {
                $query
                    ->where('status', 'available')
                    ->where('starts_at', '>=', now());
            })
            ->with([
                'category',
                'posterMedia',
                'coverMedia',
                'schedules' => function ($query): void {
                    $query
                        ->where('status', 'available')
                        ->where('starts_at', '>=', now())
                        ->with([
                            'venue',
                            'venueLayout',
                            'ticketTypes' => fn ($ticketQuery) => $ticketQuery
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('price_amount'),
                        ])
                        ->orderBy('starts_at');
                },
            ]);

        $eventsQuery
            ->when($request->filled('q'), function ($query) use ($request): void {
                $search = trim((string) $request->string('q'));
                $query->where(function ($query) use ($search): void {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('summary', 'like', "%{$search}%")
                        ->orWhere('discipline', 'like', "%{$search}%")
                        ->orWhere('event_type', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('category'), fn ($query) => $query
                ->whereHas('category', fn ($categoryQuery) => $categoryQuery
                    ->where('slug', $request->string('category'))))
            ->when($request->get('period') === 'week', fn ($query) => $query
                ->whereHas('schedules', fn ($scheduleQuery) => $scheduleQuery
                    ->where('status', 'available')
                    ->whereBetween('starts_at', [now(), now()->endOfWeek()])))
            ->when($request->get('period') === 'month', fn ($query) => $query
                ->whereHas('schedules', fn ($scheduleQuery) => $scheduleQuery
                    ->where('status', 'available')
                    ->whereBetween('starts_at', [now(), now()->endOfMonth()])));

        $events = $eventsQuery
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(12)
            ->withQueryString();

        $categories = EventCategory::query()
            ->whereIn('id', Event::query()
                ->where('status', ContentStatus::Published->value)
                ->whereHas('schedules', fn ($query) => $query
                    ->where('status', 'available')
                    ->where('starts_at', '>=', now()))
                ->whereNotNull('category_id')
                ->select('category_id'))
            ->orderBy('name')
            ->get();

        return view('cartelera.index', compact('events', 'categories'));
    }

    public function show(string $slug): View
    {
        $event = Event::query()
            ->where('slug', $slug)
            ->where('status', ContentStatus::Published->value)
            ->with([
                'category',
                'posterMedia',
                'coverMedia',
                'mobileMedia',
                'seoImageMedia',
                'participants' => fn ($query) => $query
                    ->where('is_public', true)
                    ->with(['person', 'organization'])
                    ->orderBy('sort_order'),
                'schedules' => function ($query): void {
                    $query
                        ->where('status', 'available')
                        ->where('starts_at', '>=', now())
                        ->with([
                            'venue',
                            'venueLayout',
                            'ticketTypes' => fn ($ticketQuery) => $ticketQuery
                                ->where('is_active', true)
                                ->orderBy('sort_order')
                                ->orderBy('price_amount'),
                        ])
                        ->orderBy('starts_at');
                },
            ])
            ->firstOrFail();

        return view('cartelera.show', compact('event'));
    }

    public function calendar(string $slug, EventSchedule $schedule): Response
    {
        $event = Event::query()
            ->where('slug', $slug)
            ->where('status', ContentStatus::Published->value)
            ->firstOrFail();

        abort_unless($schedule->event_id === $event->id, 404);

        $schedule->loadMissing('venue');
        $startsAt = $schedule->starts_at->copy()->utc();
        $endsAt = ($schedule->ends_at ?? $schedule->starts_at->copy()->addHours(2))->copy()->utc();
        $venue = $schedule->venue?->title ?? $schedule->venue?->name ?? 'Centro Cultural Domingo Soler';
        $escape = static fn (?string $value): string => str_replace(
            ["\\", ";", ",", "\r\n", "\n", "\r"],
            ["\\\\", "\\;", "\\,", "\\n", "\\n", "\\n"],
            $value ?? ''
        );

        $calendar = implode("\r\n", [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//Centro Cultural Domingo Soler//Cartelera//ES',
            'CALSCALE:GREGORIAN',
            'METHOD:PUBLISH',
            'BEGIN:VEVENT',
            'UID:evento-' . $event->id . '-funcion-' . $schedule->id . '@domingosoler.mx',
            'DTSTAMP:' . now()->utc()->format('Ymd\\THis\\Z'),
            'DTSTART:' . $startsAt->format('Ymd\\THis\\Z'),
            'DTEND:' . $endsAt->format('Ymd\\THis\\Z'),
            'SUMMARY:' . $escape($event->title),
            'DESCRIPTION:' . $escape($event->summary . '\n' . route('cartelera.show', $event->slug)),
            'LOCATION:' . $escape($venue),
            'URL:' . route('cartelera.show', $event->slug),
            'END:VEVENT',
            'END:VCALENDAR',
            '',
        ]);

        return response($calendar, 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="' . $event->slug . '.ics"',
        ]);
    }

    public function storeReservation(
        Request $request,
        string $slug,
        EventSchedule $schedule
    ): RedirectResponse {
        $event = Event::query()
            ->where('slug', $slug)
            ->where('status', ContentStatus::Published->value)
            ->firstOrFail();

        abort_unless(
            $schedule->event_id === $event->id
                && $schedule->status === 'available'
                && $schedule->starts_at->isFuture(),
            404
        );

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:180'],
            'customer_phone' => ['required', 'string', 'max:40'],
            'customer_email' => ['nullable', 'email', 'max:190'],
            'quantity' => ['required', 'integer', 'min:1', 'max:10'],
            'internal_note' => ['nullable', 'string', 'max:2000'],
        ]);

        $unitPrice = (float) (
            $schedule->price_amount
            ?? $event->reference_price_amount
            ?? 0
        );

        $isComplimentary = $event->is_free || $unitPrice <= 0;

        $reservation = Reservation::create([
            'event_schedule_id' => $schedule->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? null,
            'quantity' => $data['quantity'],
            'unit_price' => $unitPrice,
            'total_amount' => $unitPrice * $data['quantity'],
            'currency' => $schedule->currency ?? 'MXN',
            'source' => 'web',
            'status' => 'active',
            'payment_status' => $isComplimentary
                ? 'complimentary'
                : 'pending',
            'internal_note' => $data['internal_note'] ?? null,
        ]);

        return redirect()->route('cartelera.reservation.success', [
            'slug' => $event->slug,
            'reservation' => $reservation->folio,
        ]);
    }

    public function reservationSuccess(string $slug, string $reservation): View
    {
        $event = Event::query()
            ->where('slug', $slug)
            ->where('status', ContentStatus::Published->value)
            ->firstOrFail();

        $reservation = Reservation::query()
            ->where('folio', $reservation)
            ->whereHas('eventSchedule', fn ($query) => $query->where('event_id', $event->id))
            ->firstOrFail();

        return view('cartelera.reservation-success', compact('event', 'reservation'));
    }
}
