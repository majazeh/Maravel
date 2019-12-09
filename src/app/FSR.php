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
        $size   = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
        $factor = floor((strlen($this->bytes) - 1) / 3);

        return sprintf("%.2f ", $this->bytes / pow(1024, $factor)) . @$size[$factor];
    }

    public static function make($bytes)
    {
        return new static($bytes);
    }
}
