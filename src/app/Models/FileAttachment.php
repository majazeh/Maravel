<?php

namespace App\Models;

use App\File;
use App\Post;

class FileAttachment
{
    protected $input, $post, $file;

    public $disk = 'public';
    public function __construct($input)
    {
        $this->input = $input;
    }

    public function createPost(array $data = null)
    {
        $data = array_merge([
            'type' => 'attachment',
            'status' => 'draft',
            'creator_id' => auth()->id()
        ], $data);

        $this->post = Post::create($data);
        return $this->post;
    }

    public function createFile(array $data = [])
    {
        $this->file = File::move($this->post, $this->input, $this->disk, $data);
        return $this->file;
    }

    public function post()
    {
        return $this->post;
    }

    public function file()
    {
        return $this->file;
    }

    public function input()
    {
        return $this->input;
    }
}
