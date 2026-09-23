<?php

namespace App\Http\Controllers;

use App\Enums\ContentStatus;
use App\Models\Event;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __invoke(): View
    {
        $upcomingQuery = $this->publishedEvents()
            ->whereHas('schedules', fn (Builder $query) => $query
                ->where('status', 'available')
                ->where('starts_at', '>=', now()))
            ->with([
                'category',
                'posterMedia',
                'coverMedia',
                'mobileMedia',
                'schedules' => fn ($query) => $query
                    ->where('status', 'available')
                    ->where('starts_at', '>=', now())
                    ->with([
                        'venue',
                        'ticketTypes' => fn ($ticketQuery) => $ticketQuery
                            ->where('is_active', true)
                            ->orderBy('sort_order')
                            ->orderBy('price_amount'),
                    ])
                    ->orderBy('starts_at'),
            ])
            ->orderByDesc('show_on_home')
            ->orderByDesc('is_featured')
            ->orderBy('sort_order');

        // La misma colección alimenta el slider y la cartelera. El evento
        // destacado también debe repetirse en la cuadrícula inferior.
        $upcomingEvents = (clone $upcomingQuery)->get();
        $sliderEvents = $upcomingEvents->values();

        $archiveEvents = $this->publishedEvents()
            ->where('show_in_archive', true)
            ->where(function (Builder $query): void {
                $query
                    ->where('finished_at', '<=', now())
                    ->orWhereDoesntHave('schedules', fn (Builder $scheduleQuery) => $scheduleQuery
                        ->where('status', 'available')
                        ->where('starts_at', '>=', now()));
            })
            ->with([
                'category',
                'posterMedia',
                'coverMedia',
                'schedules' => fn ($query) => $query->latest('starts_at'),
            ])
            ->orderByDesc('finished_at')
            ->orderByDesc('updated_at')
            ->limit(4)
            ->get();

        $categories = $upcomingEvents
            ->map(fn (Event $event) => $event->category)
            ->filter()
            ->unique('id')
            ->values();

        return view('home', compact(
            'sliderEvents',
            'upcomingEvents',
            'archiveEvents',
            'categories',
        ));
    }

    private function publishedEvents(): Builder
    {
        return Event::query()
            ->where('status', ContentStatus::Published->value)
            ->where(fn (Builder $query) => $query
                ->whereNull('publish_starts_at')
                ->orWhere('publish_starts_at', '<=', now()))
            ->where(fn (Builder $query) => $query
                ->whereNull('publish_ends_at')
                ->orWhere('publish_ends_at', '>=', now()));
    }
}
