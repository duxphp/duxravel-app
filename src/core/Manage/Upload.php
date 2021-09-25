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
        $dirId = \Duxravel\Core\Model\FileDir::where('dir_id', $id)->value('dir_id');
        if (empty($dirId)) {
            $dirId = \Duxravel\Core\Model\FileDir::where('has_type', $this->getHasType())->orderBy('dir_id',
                'desc')->value('dir_id');
        }
        if (empty($dirId)) {
            $dirId = \Duxravel\Core\Model\FileDir::insertGetId([
                'name' => '默认',
                'has_type' => $this->getHasType()
            ]);
        }

        $data = \Duxravel\Core\Util\Upload::load($this->getHasType(), [
            'thumb' => $request->get('thumb'),
            'width' => $request->get('width'),
            'height' => $request->get('height'),
            'water' => $request->get('water'),
            'alpha' => $request->get('alpha'),
            'source' => resource_path(config('image.water_image'))
        ],$dirId, $this->driver);
        if (empty($data)) {
            app_error('上传文件失败');
        }
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

        $dirId = \Duxravel\Core\Model\FileDir::where('has_type', $this->getHasType())->orderBy('dir_id',
            'desc')->value('dir_id');
        if (empty($dirId)) {
            $dirId = \Duxravel\Core\Model\FileDir::insertGetId([
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
            \Duxravel\Core\Model\File::insert($upload);
        }
        return app_success('图片获取成功', $data);
    }
}
