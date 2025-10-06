<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Facades\LogBatch;
use Spatie\Activitylog\Facades\Activity as ActivityFacade;

trait LogsActivityCustom
{
    protected function logActivity(string $description, ?array $properties = [], $subject = null): void
    {
        $activity = ActivityFacade::causedBy(auth()->user() ?? null);

        if ($subject !== null) {
            $activity->performedOn($subject);
        }

        $activity->withProperties($properties ?? [])
            ->log($description);
    }
}