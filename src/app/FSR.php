<?php

namespace App;

// File size readble
class FSR {
    protected $bytes = 0;

    public function __construct($bytes)
    {
        $this->bytes = $bytes;
    }

    public function __toString()
    {
        $i = floor(log($this->bytes, 1024));
        $x = round($this->bytes / pow(1024, $i), [0, 0, 2, 2, 3][$i]) . ' ' . ['b', 'kb', 'mb', 'gb', 'tb'][$i];
        dd(pow(3, 3));
    }

    public static function make($bytes)
    {
        return new static($bytes);
    }
}
