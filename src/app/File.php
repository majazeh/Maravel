<?php

namespace App;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\UploadedFile;
use Maravel\Controllers\API\AttachmentController;
use Image;
use DB;
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

    public static function upload($request, $file)
    {
        $attachmentController = new AttachmentController($request);
        $attachmentRequest = new $request;
        $attachmentRequest->files->add(['file' => UploadedFile::createFromBase($request->file($file))]);
        $attachment = $attachmentController->store($attachmentRequest);
        return $attachment;
    }

    public static function move(Post $post, UploadedFile $temp, $mode = 'original', $data = [])
    {
        $type = explode('/', $temp->getMimeType());
        $type = $type[0];

        $file_name = $post->serial . '_original.' . $temp->extension();
        $file = static::create(
            array_merge_recursive([
            'post_id' => $post->id,
            'mode' => 'original',
            'slug' => '',
            'url' => '',
            'dir' => '',
            'mime' => $temp->getMimeType(),
            'exec' => $temp->extension(),
            'type' => $type,
            ], $data));
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
    public static function imageSize($post, $width, $height = null, $mode = null){
        if(!$mode)
        {
            $mode = "{$width}x";
            if($height)
            {
                $mode .= $height;
            }
        }
        $height = $height ?: $width;
        $original = static::where('post_id', $post->id)->where('mode', 'original')->first();
        DB::beginTransaction();
        $file_name = $post->serial . "_$mode." . $original->exec;
        $file = static::create([
            'post_id' => $post->id,
            'mode' => $mode,
            'slug' => '',
            'url' => '',
            'dir' => '',
            'mime' => $original->mime,
            'exec' => $original->exec,
            'type' => $original->type,
        ]);
        $folder_int = (string) (ceil($file->id / 1000) * 1000);
        $folder = 'storage/Files_' . $folder_int;
        $file_slug = "$folder/$file_name";
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }

        $file->slug = $file_slug;
        $file->url = asset($file_slug);
        $file->dir = public_path($file_slug);
        $file->save();

        $image = Image::make($original->dir)
        ->resize($width, $height)
        ->save($file->dir);
        DB::commit();
    }
}
