<?php

namespace Duxravel\Core\Util;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

/**
 * 上传处理
 */
class Upload
{

    public static function load($hasType, $config = [], $dirId = 0, $driver = ''): array
    {
        $thumb = $config['thumb'] ?? config('image.thumb');
        $width = $config['width'] ?? config('image.thumb_width');
        $height = $config['height'] ?? config('image.thumb_height');
        $water = $config['water'] ?? config('image.water');
        $alpha = $config['alpha'] ?? config('image.water_alpha');
        $source = resource_path(config('image.water_image'));

        $files = request()->allFiles();
        $ids = [];
        if (is_array($files)) {
            foreach ($files as $field => $file) {
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
                $path = $file->store('upload/' . date('Y-m-d'), $driver);
                if ($path) {
                    $tmp = [
                        'dir_id' => $dirId,
                        'has_type' => $hasType,
                        'driver' => $driver ?: config('filesystems.default'),
                        'url' => Storage::disk($driver)->url($path),
                        'field' => $field,
                        'path' => $path,
                        'title' => $file->getClientOriginalName(),
                        'ext' => $file->extension(),
                        'mime' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'create_time' => time(),
                        'update_time' => time()
                    ];
                    $ids[] = \Duxravel\Core\Model\File::insertGetId($tmp);
                } else {
                    app_error('上传失败');
                }
            }
        }
        $data = \Duxravel\Core\Model\File::where('has_type', $hasType)->whereIn('file_id', $ids)->get([
            'file_id', 'dir_id', 'url', 'title', 'ext', 'size', 'create_time'
        ]);

        return $data->map(function ($item) {
            $item->size = app_filesize($item['size']);
            $item->time = $item->create_time->format('Y-m-d H:i:s');
            return $item;
        })->toArray();
    }


}

