<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    private function supportsChecks(): bool
    {
        return DB::getDriverName() === 'mysql';
    }

    public function up(): void
    {
        if (! $this->supportsChecks()) {
            return;
        }

        DB::statement("ALTER TABLE event_participants ADD CONSTRAINT ck_event_participant_one_subject CHECK ((person_id IS NOT NULL AND organization_id IS NULL) OR (person_id IS NULL AND organization_id IS NOT NULL))");
        DB::statement('ALTER TABLE event_schedules ADD CONSTRAINT ck_schedule_end_after_start CHECK (ends_at IS NULL OR ends_at > starts_at)');
        DB::statement('ALTER TABLE event_schedules ADD CONSTRAINT ck_schedule_authorized_capacity CHECK (authorized_capacity > 0)');
        DB::statement('ALTER TABLE event_schedules ADD CONSTRAINT ck_schedule_public_capacity CHECK (public_capacity <= authorized_capacity)');
        DB::statement('ALTER TABLE event_schedules ADD CONSTRAINT ck_schedule_operational_capacity CHECK (public_capacity + production_capacity <= authorized_capacity)');
        DB::statement('ALTER TABLE event_schedules ADD CONSTRAINT ck_schedule_complimentary_capacity CHECK (complimentary_capacity <= public_capacity)');
        DB::statement('ALTER TABLE event_schedules ADD CONSTRAINT ck_schedule_price_nonnegative CHECK (price_amount IS NULL OR price_amount >= 0)');
        DB::statement("ALTER TABLE event_schedules ADD CONSTRAINT ck_schedule_capacity_override CHECK (authorized_capacity <= base_capacity_snapshot OR (capacity_override_authorized = 1 AND capacity_override_reason IS NOT NULL AND capacity_override_at IS NOT NULL))");        
        DB::statement('ALTER TABLE events ADD CONSTRAINT ck_event_publication_dates CHECK (publish_ends_at IS NULL OR publish_starts_at IS NULL OR publish_ends_at > publish_starts_at)');
        DB::statement('ALTER TABLE home_slides ADD CONSTRAINT ck_home_slide_dates CHECK (ends_at IS NULL OR starts_at IS NULL OR ends_at > starts_at)');
        DB::statement('ALTER TABLE rental_requests ADD CONSTRAINT ck_rental_attendance_positive CHECK (expected_attendance IS NULL OR expected_attendance > 0)');
        DB::statement('ALTER TABLE rental_requests ADD CONSTRAINT ck_rental_budget_nonnegative CHECK (budget_amount IS NULL OR budget_amount >= 0)');
    }

    public function down(): void
    {
        if (! $this->supportsChecks()) {
            return;
        }

        foreach (['ck_event_participant_one_subject'] as $constraint) {
            DB::statement("ALTER TABLE event_participants DROP CHECK {$constraint}");
        }

        foreach (['ck_schedule_end_after_start', 'ck_schedule_authorized_capacity', 'ck_schedule_public_capacity', 'ck_schedule_operational_capacity', 'ck_schedule_complimentary_capacity', 'ck_schedule_price_nonnegative', 'ck_schedule_capacity_override'] as $constraint) {
            DB::statement("ALTER TABLE event_schedules DROP CHECK {$constraint}");
        }

        DB::statement('ALTER TABLE events DROP CHECK ck_event_publication_dates');
        DB::statement('ALTER TABLE home_slides DROP CHECK ck_home_slide_dates');
        DB::statement('ALTER TABLE rental_requests DROP CHECK ck_rental_attendance_positive');
        DB::statement('ALTER TABLE rental_requests DROP CHECK ck_rental_budget_nonnegative');
    }
};
