<?php

namespace App\Http\Resources;
use Illuminate\Http\Resources\Json\JsonResource;
use Cache;
use Str;
class PrivateFile extends JsonResource
{
    public const CACHE_TIME = 5 * 60;
    public const CACHE_KEY_LENGTH = 80;
    public $private_key;
    public function __construct($resource, $private_key = null)
    {
        parent::__construct($resource);
        if(!$private_key)
        {
            $private_key = Str::random(static::CACHE_KEY_LENGTH);
            Cache::put($private_key, [$this->PrivateIndex()], static::CACHE_TIME);
        }
        $this->private_key = $private_key;

    }
    public function toArray($request)
    {
        $file = explode('/', $this->slug);
        return [
            'id' => $this->serial,
            'file_name' => last($file),
            'slug' => $this->slug,
            'url' => $this->url .'?private_key=' . $this->private_key,
            'type' => $this->type,
            'mime' => $this->mime,
            'exec' => $this->exec,
            'created_at' => ($this->created_at instanceof \Carbon\Carbon) ? $this->created_at->timestamp : $this->created_at,
            'updated_at' => ($this->updated_at instanceof \Carbon\Carbon) ? $this->updated_at->timestamp : $this->updated_at,
        ];
    }

    public function PrivateIndex()
    {
        preg_match("/\/([^\/\.]*\..+)$/", $this->slug, $index);
        return $index[1];
    }
}
