<?php

namespace Microlise;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\App;

class MediaType extends Model
{
    const VIDEO = 1;
    const IMAGES = 2;
    const DOCUMENTS = 3;
    const MP3 = 4;
    const LINKS = 5;

    /**
     * @var string
     */
    protected $table = 'media_types';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function media()
    {
        return $this->hasMany(Media::class);

    }

}
