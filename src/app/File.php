<?php

namespace App;

use App\Models\FileAttachment;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Http\UploadedFile;
use Maravel\Controllers\API\AttachmentController;
use Image;
use DB;
use Closure;
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

    public static function attachment($file, Closure $callaback = null)
    {
        $attachment = new FileAttachment($file);
        if($callaback)
        {
            $result = call_user_func_array($callaback, [$attachment]);
            if($result)
            {
                return $result;
            }
        }
        else
        {
            $attachment->createPost();
            $attachment->createFile();
        }
        return $attachment->post();
    }

    public static function upload($request, $file)
    {
        $attachmentController = new AttachmentController($request);
        $attachmentRequest = new $request;
        $attachmentRequest->files->add(['file' => UploadedFile::createFromBase($request->file($file))]);
        $attachment = $attachmentController->store($attachmentRequest);
        return $attachment;
    }

    public static function move(Post $post, UploadedFile $temp, $disk = null, $data = [])
    {
        return static::specialMove([
            'post' => $post,
            'temp' => $temp,
            'disk' => $disk,
            'data' => $data,

        ]);
    }

    public static function specialMove($options = []){
        $temp = $options['temp'];
        $post = $options['post'];
        $data = isset($options['data']) ? $options['data'] : [];
        $disk = isset($options['disk']) ? $options['disk'] : null;
        $disk = config('filesystems.disks.' . $disk, config('filesystems.disks.public'));

        $type = explode('/', $temp->getMimeType());
        $type = $type[0];
        $mode = isset($data['mode'])  ? $data['mode'] : 'original';
        $file_name = $post->serial . "_$mode." . $temp->extension();
        $folders = glob(join(DIRECTORY_SEPARATOR, [$disk['root'], 'Files_*']));
        $last_folder = last($folders);
        $files_count = count(glob(join(DIRECTORY_SEPARATOR, [$last_folder, '*'])));
        $folder_int = (string) (ceil($files_count / 1000) * 1000);

        $folder_name = 'Files_' . $folder_int;
        $folder = join(DIRECTORY_SEPARATOR, [$disk['root'], $folder_name]);
        $file_slug = trim(str_replace(env('APP_URL'), '', join('/', [$disk['url'], $folder_name, $file_name])), '/');
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }
        $file = static::create(
            array_merge([
                'post_id' => $post->id,
                'mode' => 'original',
                'slug' => $file_slug,
                'url' => join('/', [$disk['url'], $folder_name, $file_name]),
                'dir' => join(DIRECTORY_SEPARATOR, [$folder, $file_name]),
                'mime' => $temp->getMimeType(),
                'exec' => $temp->extension(),
                'type' => $type,
            ], $data)
        );
        try{
            $temp->move($folder, $file_name);
        }catch (\Exception $e)
        {
            copy($temp->getPathName(), join(DIRECTORY_SEPARATOR, [$folder, $file_name]));
        }

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
    }

    public function changeSize($width, $height = null, $mode = null, $disk = null)
    {
        if (!$mode) {
            $mode = "{$width}x";
            if ($height) {
                $mode .= $height;
            }
        }
        $height = $height ?: $width;
        $original = null;
        if($this->mode != 'original')
        {
            $original = static::where('post_id', $this->post_id)->where('mode', 'original')->first();
        }
        if(!$original)
        {
            $original = $this;
        }


        $disk = config('filesystems.disks.' . $disk, config('filesystems.disks.public'));

        $file_name = Post::decode_id($this->post_id) . "_$mode." . $original->exec;
        $folders = glob(join(DIRECTORY_SEPARATOR, [$disk['root'], 'Files_*']));
        $last_folder = last($folders);
        $files_count = count(glob(join(DIRECTORY_SEPARATOR, [$last_folder, '*'])));
        $folder_int = (string) (ceil($files_count / 1000) * 1000);

        $folder_name = 'Files_' . $folder_int;
        $folder = join(DIRECTORY_SEPARATOR, [$disk['root'], $folder_name]);
        $file_slug = trim(str_replace(env('APP_URL'), '', join('/', [$disk['url'], $folder_name, $file_name])), '/');
        if (!file_exists(public_path($folder))) {
            mkdir(public_path($folder), 0777, true);
        }
        $file = static::create([
            'post_id' => $this->post_id,
            'mode' => $mode,
            'slug' => $file_slug,
            'url' => join('/', [$disk['url'], $folder_name, $file_name]),
            'dir' => join(DIRECTORY_SEPARATOR, [$folder, $file_name]),
            'mime' => $original->mime,
            'exec' => $original->exec,
            'type' => $original->type,
        ]);

        return Image::make($original->dir)
            ->resize($width, $height)
            ->save($file->dir);
    }

    public function remove(){
        unlink($this->dir);
        $this->delete();
    }
}
