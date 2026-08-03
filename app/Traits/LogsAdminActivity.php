<?php

namespace App\Traits;

use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Support\Str;

trait LogsAdminActivity
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logFillable()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->useLogName($this->getLogNameForActivity());
    }

    /**
     * Menentukan nama modul (log_name) otomatis dari nama Model,
     * contoh: App\Models\SacrificialAnimal -> 'sacrificial-animal'
     */
    protected function getLogNameForActivity(): string
    {
        return Str::kebab(class_basename($this));
    }

    /**
     * Menambahkan IP Address ke setiap catatan log secara otomatis.
     */
    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->properties = $activity->properties->merge([
            'ip_address' => request()->ip(),
        ]);
    }
}
