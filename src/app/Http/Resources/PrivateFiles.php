<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;
use Str;
use Cache;

class PrivateFiles extends ResourceCollection
{
    public function toArray($request)
    {
        $data = [];
        $private_key = Str::random(PrivateFile::CACHE_KEY_LENGTH);
        $ids = [];
        foreach ($this->resource as $key => $value) {
            $file = new PrivateFile($value, $private_key);;
            $ids[] = $file->privateIndex();
            $data[$file->mode] = $file;
        }
        if(count($data))
        {
            Cache::put($private_key, $ids, PrivateFile::CACHE_TIME);
        }
        return count($data) ? $data : null;
    }
}
