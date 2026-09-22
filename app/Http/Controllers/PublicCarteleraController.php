<?php

namespace App\Http\Controllers;

use App\Enums\ContentStatus;
use App\Models\Event;
use App\Models\EventSchedule;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PublicCarteleraController extends Controller
{
    public function index(): View
    {
        $events = Event::query()
            ->where('status', ContentStatus::Published->value)
            ->whereHas('schedules', function ($query): void {
                $query
                    ->where('status', 'available')
                    ->where('starts_at', '>=', now());
            })
            ->with([
                'schedules' => function ($query): void {
                    $query
                        ->where('status', 'available')
                        ->where('starts_at', '>=', now())
                        ->orderBy('starts_at');
                },
            ])
            ->orderByDesc('is_featured')
            ->orderBy('sort_order')
            ->paginate(12);

        return view('cartelera.index', compact('events'));
    }

    public function show(string $slug): View
    {
        $event = Event::query()
            ->where('slug', $slug)
            ->where('status', ContentStatus::Published->value)
            ->with([
                'schedules' => function ($query): void {
                    $query
                        ->where('status', 'available')
                        ->where('starts_at', '>=', now())
                        ->orderBy('starts_at');
                },
            ])
            ->firstOrFail();

        return view('cartelera.show', compact('event'));
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