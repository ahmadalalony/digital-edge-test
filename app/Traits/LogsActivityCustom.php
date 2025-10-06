<?php

namespace App\Traits;

use Spatie\Activitylog\Models\Activity;
use Spatie\Activitylog\Facades\LogBatch;
use Spatie\Activitylog\Facades\Activity as ActivityFacade;

trait LogsActivityCustom
{
    protected function logActivity(string $description, ?array $properties = [], $subject = null): void
    {
        ActivityFacade::causedBy(auth()->user() ?? null)
            ->performedOn($subject)
            ->withProperties($properties ?? [])
            ->log($description);
    }
}