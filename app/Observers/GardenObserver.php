<?php

namespace App\Observers;

use App\Models\Garden;

class GardenObserver
{
    /**
     * Saat Garden di-soft-delete, cascade ke semua relasi child.
     * Data tetap ada di DB dan bisa di-restore.
     */
    public function deleting(Garden $garden): void
    {
        // Hanya cascade jika ini SoftDelete (bukan force delete)
        if (! $garden->isForceDeleting()) {
            $garden->afdelings()->each(fn ($afdeling) => $afdeling->delete());
            $garden->visits()->each(fn ($visit) => $visit->delete());
        }
    }

    /**
     * Saat Garden di-restore, restore semua child yang di-delete bersamaan.
     */
    public function restored(Garden $garden): void
    {
        $garden->afdelings()->withTrashed()->each(fn ($afdeling) => $afdeling->restore());
        $garden->visits()->withTrashed()->each(fn ($visit) => $visit->restore());
    }
}
