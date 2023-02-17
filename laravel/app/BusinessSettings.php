<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BusinessSettings extends Model
{
    protected $table = 'business_settings';

    protected $fillable = [
        'business_id',
        'type',
        'value',
    ];

    /**
     * Business Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }
}
