<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Classification extends Model
{
    protected $table = 'classifications';

    protected $guarded = [];

    /**
     * Business Relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Returns an array with the default classifications for business creation.
     * @return array
     */
    public function getDefaultClassifications(): array
    {
        return [
            [
                'name' => 'Full-Time',
                'is_base' => 1,
                'is_enabled' => 1,
                'minimum_hours' => 8,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 8,
                'maximum_hours_interval' => 'day',
            ],
            [
                'name' => 'Part-Time',
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day',
            ],
            [
                'name' => 'Part-Time 1',
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day',
            ],
            [
                'name' => 'Part-Time 2',
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day',
            ],
            [
                'name' => 'Per-Diem',
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day',
            ],
            [
                'name' => 'Temporary',
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day',
            ],
            [
                'name' => 'Commission',
                'is_base' => 0,
                'is_enabled' => 1,
                'minimum_hours' => 4,
                'minimum_hours_interval' => 'day',
                'maximum_hours' => 6,
                'maximum_hours_interval' => 'day',
            ],
        ];
    }
}
