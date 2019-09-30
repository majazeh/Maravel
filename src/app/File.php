<?php

namespace App;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\UploadedFile;

class File extends Eloquent
{
    use Models\Model;
    use Models\Serial;

    protected $guarded = [
        'id'
    ];


    public static $s_prefix = 'F';
    public static $s_start = 24300000;
    public static $s_end = 728999999;

    public static function move(Post $post, UploadedFile $temp, $mode = 'original')
    {
        $type = explode('/', $temp->getMimeType());
        $type = $type[0];

        $file_name = $post->serial . '_original.' . $temp->extension();
        $file = static::create([
            'post_id' => $post->id,
            'mode' => 'original',
            'slug' => '',
            'url' => '',
            'dir' => '',
            'mime' => $temp->getMimeType(),
            'exec' => $temp->extension(),
            'type' => $type,
            'mode' => 'original',
        ]);
        $folder_int = (string) (ceil($file->id / 1000) * 1000);
        $folder = 'storage/Files_' . $folder_int;
        $file_slug = "$folder/$file_name";
        if(!file_exists(public_path($folder)))
        {
            mkdir(public_path($folder), 0777, true);
        }

        $file->slug = $file_slug;
        $file->url = asset($file_slug);
        $file->dir = public_path($file_slug);
        $file->save();

        $temp->move($folder, $file_name);

        return $file;
    }

}
