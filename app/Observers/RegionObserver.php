<?php

namespace App\Observers;

use App\Models\Region;

class RegionObserver
{
    /**
     * Saat Region di-soft-delete, cascade ke semua Gardens (yang kemudian cascade ke children-nya via GardenObserver).
     */
    public function deleting(Region $region): void
    {
        if (! $region->isForceDeleting()) {
            $region->gardens()->each(fn ($garden) => $garden->delete());
        }
    }

    /**
     * Saat Region di-restore, restore semua Gardens (yang kemudian restore children-nya).
     */
    public function restored(Region $region): void
    {
        $region->gardens()->withTrashed()->each(fn ($garden) => $garden->restore());
    }
}
