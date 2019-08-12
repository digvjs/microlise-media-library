<?php

namespace Microlise;

use Illuminate\Database\Eloquent\Model;

class MediaMeta extends Model
{
    /**
     * @var string
     */
    protected $table = 'media_meta';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function media()
    {
        return $this->belongsTo(Media::class);
    }
}
