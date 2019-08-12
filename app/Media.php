<?php

namespace Microlise;

use Illuminate\Database\Eloquent\Model;

class Media extends Model
{
    /**
     * @var string
     */
    protected $table = 'media';

    /**
     * @var array
     */
    protected $fillable = ['type_id', 'path', 'name', 'description'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function mediaType()
    {
        return $this->belongsTo(MediaType::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function mediaMeta()
    {
        return $this->hasOne(MediaMeta::class);
    }

    /**
     * Boot method before making changes to Media model
     */
    public static function boot() {
        parent::boot();

        static::deleting(function($media) {
            $media->mediaMeta()->delete();
        });
    }
}
