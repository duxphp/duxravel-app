<?php

namespace Duxravel\Core\Manage;

use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

trait Upload
{

    /**
     * 文件关联类型
     * @var string
     */
    public string $hasType = '';

    /**
     * 获取关联类型
     * @return mixed
     */
    public function getHasType()
    {
        if ($this->hasType) {
            return $this->hasType;
        }
        $parsing = app_parsing();
        $this->hasType = strtolower($parsing['layer']);
        return $this->hasType;
    }

    /**
     * 文件上传
     * @param Request $request
     * @return array|void
     */
    public function ajax(Request $request)
    {
        $id = (int)$request->get('id') ?: 0;
        $dirId = module('Common.Model.FileDir')->where('dir_id', $id)->value('dir_id');
        if (empty($dirId)) {
            $dirId = module('Common.Model.FileDir')->where('has_type', $this->getHasType())->orderBy('dir_id',
                'desc')->value('dir_id');
        }
        if (empty($dirId)) {
            $dirId = module('Common.Model.FileDir')->insertGetId([
                'name' => '默认',
                'has_type' => $this->getHasType()
            ]);
        }

        $thumb = $request->get('thumb') ?? config('image.thumb');
        $width = $request->get('width') ?? config('image.thumb_width');
        $height = $request->get('height') ?? config('image.thumb_height');
        $water = $request->get('water') ?? config('image.water');
        $alpha = $request->get('alpha') ?? config('image.water_alpha');
        $source = resource_path(config('image.water_image'));

        $files = request()->allFiles();
        $ids = [];
        if (is_array($files)) {
            foreach ($files as $file) {
                $ext = $file->extension();
                if (in_array($ext, ['jpg', 'png', 'bmp', 'jpeg', 'gif'])) {
                    $tmpPath = $file->getRealPath();
                    $image = Image::make($file);
                    if ($thumb) {
                        switch ($thumb) {
                            // 居中裁剪缩放
                            case 'center':
                                $image->fit($width, $height, function ($constraint) {
                                    $constraint->upsize();
                                }, 'center');
                                break;
                            // 固定尺寸
                            case 'fixed':
                                $image->resize($width, $height, function ($constraint) {
                                    $constraint->upsize();
                                });
                                break;
                            // 等比例缩放
                            case 'scale':
                                if ($width > $height) {
                                    $image->resize(null, $height, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                } else {
                                    $image->resize($width, null, function ($constraint) {
                                        $constraint->aspectRatio();
                                        $constraint->upsize();
                                    });
                                }
                        }
                    }
                    if ($water) {
                        switch ($water) {
                            //左上角水印
                            case 1:
                            case 'top-left':
                                $position = 'top-left';
                                break;
                            //上居中水印
                            case 2:
                            case 'top':
                                $position = 'top';
                                break;
                            //右上角水印
                            case 3:
                            case 'top-right':
                                $position = 'top-right';
                                break;
                            //左居中水印
                            case 4:
                            case 'left':
                                $position = 'left';
                                break;
                            //居中水印
                            default:
                            case 5:
                            case 'center':
                                $position = 'center';
                                break;
                            //右居中水印
                            case 6:
                            case 'right':
                                $position = 'right';
                                break;
                            //左下角水印
                            case 7:
                            case 'bottom-left':
                                $position = 'bottom-left';
                                break;
                            //下居中水印
                            case 8:
                            case 'bottom':
                                $position = 'bottom';
                                break;
                            //右下角水印
                            case 9:
                            case 'bottom-right':
                                $position = 'bottom-right';
                                break;
                        }
                        $watermark = Image::make($source)->opacity($alpha);
                        $image->insert($watermark, $position, 10, 10);
                    }
                    $image->save($tmpPath);
                }
                $path = $file->store('upload/' . date('Y-m-d'), $this->driver);
                if ($path) {
                    $tmp = [
                        'dir_id' => $dirId,
                        'has_type' => $this->getHasType(),
                        'driver' => $this->driver ?: config('filesystems.default'),
                        'url' => Storage::disk($this->driver)->url($path),
                        'path' => $path,
                        'title' => $file->getClientOriginalName(),
                        'ext' => $file->extension(),
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'create_time' => time(),
                        'update_time' => time()
                    ];
                    $ids[] = module('Common.Model.File')->insertGetId($tmp);
                } else {
                    app_error('上传失败');
                }
            }
        }
        $data = module('Common.Model.File')->where('has_type', $this->getHasType())->whereIn('file_id', $ids)->get([
            'file_id', 'dir_id', 'url', 'title', 'ext', 'size', 'create_time'
        ]);
        $data = $data->map(function ($item) {
            $item->size = app_filesize($item['size']);
            $item->time = $item->create_time->format('Y-m-d H:i:s');
            return $item;
        })->toArray();
        return app_success('上传成功', $data);
    }

    /**
     * 远程存图
     */
    public function remote()
    {
        $data = \request()->input('files');
        $files = [];
        $domain = env('APP_URL');
        foreach ($data as $key => $file) {
            if (stripos($file, $domain, 0) === false && !preg_match("/(^\.)|(^\/)/", $file)) {
                $files[$key] = $file;
            }
        }
        if (empty($files)) {
            app_error('没有新增远程图片');
        }

        $dirId = module('Common.Model.FileDir')->where('has_type', $this->getHasType())->orderBy('dir_id',
            'desc')->value('dir_id');
        if (empty($dirId)) {
            $dirId = module('Common.Model.FileDir')->insertGetId([
                'name' => '默认',
                'has_type' => $this->getHasType()
            ]);
        }

        foreach ($data as $key => $vo) {
            if (!$files[$key]) {
                continue;
            }
            try {
                $client = new \GuzzleHttp\Client();
                $imgTmp = $client->request('get', $vo)->getBody()->getContents();
                $tmpFile = tempnam(sys_get_temp_dir(), 'upload_');
                $tmp = fopen($tmpFile, 'w');
                fwrite($tmp, $imgTmp);
                fclose($tmp);
                $size = filesize($tmpFile);
                $mime = mime_content_type($tmpFile);
                $path = Storage::disk($this->driver)->putFile('upload/' . date('Y-m-d'), $tmpFile);
                @unlink($tmpFile);
            } catch (GuzzleException $exception) {
                app_error($exception->getMessage());
            }
            $url = Storage::disk($this->driver)->url($path);
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $data[$key] = $url;

            $upload = [
                'dir_id' => $dirId,
                'has_type' => $this->getHasType(),
                'driver' => $this->driver ?: config('filesystems.default'),
                'url' => $url,
                'path' => $path,
                'title' => pathinfo($vo, PATHINFO_FILENAME) . '.' . $ext,
                'ext' => $ext,
                'mime' => $mime,
                'size' => $size,
                'create_time' => time(),
                'update_time' => time()
            ];
            module('Common.Model.File')->insert($upload);
        }
        return app_success('图片获取成功', $data);
    }
}
