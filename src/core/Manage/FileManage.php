<?php

namespace Duxravel\Core\Manage;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait FileManage
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

    public function handle(Request $request)
    {
        $type = $request->get('type');
        $id = $request->get('id');
        $name = $request->get('name');
        $query = $request->get('query');
        $filter = $request->get('filter');

        $data = [];
        if ($type == 'folder') {
            $data = $this->getFolder();
        }
        if ($type == 'files') {
            $data = $this->getFile($id, $query, $filter);
        }
        if ($type == 'files-delete') {
            $data = $this->deleteFile($id);
        }
        if ($type == 'folder-create') {
            $data = $this->createFolder($name);
        }
        if ($type == 'folder-delete') {
            $data = $this->deleteFolder($id);
        }
        return app_success('ok', $data);
    }

    /**
     * @param $dirId
     * @param string $query
     * @param string $filter
     * @return array
     */
    private function getFile($dirId, $query = '', $filter = 'all'): array
    {
        $totalPage = 1;
        $page = 1;
        $format = [
            'image' => 'jpg,png,bmp,jpeg,gif',
            'audio' => 'wav,mp3,acc,ogg',
            'video' => 'mp4,ogv,webm,ogm',
            'document' => 'doc,docx,xls,xlsx,pptx,ppt,csv,pdf',
        ];

        if ($dirId) {
            $data = \Duxravel\Core\Model\File::where('has_type', $this->getHasType())->where('dir_id', $dirId);
            if ($query) {
                $data = $data->where('title', 'like', '%' . $query . '%');
            }
            if ($filter <> 'all') {
                if ($filter === 'other') {

                    $data->whereNotIn('ext', explode(',', implode(',', $format)));
                } else {

                    $filterData = explode(',', $filter);
                    $exts = [];
                    foreach ($filterData as $vo) {
                        $exts[] = $format[$vo];
                    }
                    $exts = array_filter($exts);
                    $data->whereIn('ext', explode(',', implode(',', $exts)));
                }
            }
            $data = $data->orderBy('file_id', 'desc')->paginate(16, [
                'file_id', 'dir_id', 'url', 'title', 'ext', 'size', 'created_at'
            ]);
            $total = $data->total();
            $page = $data->currentPage();
            $data = $data->map(function ($item) use ($format) {
                $item->size = app_filesize($item['size']);
                $item->time = $item->created_at->format('Y-m-d H:i:s');
                if (in_array($item->ext, explode(',', $format['image']))) {
                    $item->cover = $item->url;
                } else {
                    $type = 'other';
                    foreach ($format as $key => $vo) {
                        if (in_array($item->ext, explode(',', $vo))) {
                            $type = $key;
                            break;
                        }
                    }
                    switch ($type) {
                        case 'audio':
                            $item->cover = '/static/system/img/icon/audio.svg';
                            break;
                        case 'video':
                            $item->cover = '/static/system/img/icon/video.svg';
                            break;
                        case 'document':
                            $item->cover = '/static/system/img/icon/doc.svg';
                            break;
                        default:
                            $item->cover = '/static/system/img/icon/other.svg';
                            break;
                    }
                }
                return $item;
            })->toArray();
        } else {
            $data = [];
        }
        return [
            'data' => $data,
            'total' => $total ?: 0,
            'page' => $page,
            'pageSize' => 16
        ];
    }

    /**
     * @return mixed
     */
    private function getFolder()
    {
        return \Duxravel\Core\Model\FileDir::where('has_type', $this->getHasType())->get()->toArray();
    }

    /**
     * @param $name
     * @return array
     */
    private function createFolder($name): array
    {
        if (empty($name)) {
            trigger_error('请输入目录名称');
        }
        $file = new \Duxravel\Core\Model\FileDir;
        $file->name = $name;
        $file->has_type = $this->getHasType();
        $file->save();
        return [
            'id' => $file->dir_id,
            'name' => $name,
        ];
    }

    /**
     * @param int $id
     * @return array
     */
    private function deleteFolder(int $id): array
    {
        if (empty($id)) {
            trigger_error('请选择目录');
        }

        $files = \Duxravel\Core\Model\File::where('has_type', $this->getHasType())->where('dir_id', $id)->get([
            'driver', 'path'
        ]);
        $files->map(function ($vo) {
            Storage::disk($vo->driver)->delete($vo->path);
        });
        \Duxravel\Core\Model\File::where('file_id', $id)->delete();
        \Duxravel\Core\Model\FileDir::where('dir_id', $id)->delete();
        return [];
    }

    /**
     * @param $ids
     * @return array
     */
    private function deleteFile($ids): array
    {
        $ids = array_filter(explode(',', $ids));
        if (empty($ids)) {
            trigger_error('请选择删除文件');
        }
        $files = \Duxravel\Core\Model\File::where('has_type', $this->getHasType())->whereIn('dir_id', $ids)->get([
            'driver', 'path'
        ]);
        $files->map(function ($vo) {
            Storage::disk($vo->driver)->delete($vo->path);
        });
        \Duxravel\Core\Model\File::whereIn('file_id', $ids)->delete();
        return [];
    }

}
