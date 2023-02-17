<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobDescription extends Model
{
    protected $table = 'job_descriptions';

    protected $guarded = [];

    protected $fillable = [];

    /**
     * Business Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * User Relationship.
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_job_description');
    }
}
