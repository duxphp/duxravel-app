<?php

namespace Duxravel\Core\UI\Form;

use Illuminate\Support\Collection;

/**
 * Class File
 * @package Duxravel\Core\UI\Form
 */
class File extends Element implements Component
{
    protected string $type = 'upload';
    protected string $url = '';
    protected string $fileUrl = '';

    /**
     * File constructor.
     * @param string $name
     * @param string $field
     * @param string $has
     */
    public function __construct(string $name, string $field, string $has = '')
    {
        $this->name = $name;
        $this->field = $field;
        $this->has = $has;
    }

    public function type($type = 'upload')
    {
        $this->type = $type;
        return $this;
    }



    /**
     * 上传地址
     * @param string $url
     * @return $this
     */
    public function url(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 文件地址
     * @param string $url
     * @return $this
     */
    public function fileUrl(string $url): self
    {
        $this->fileUrl = $url;
        return $this;
    }

    /**
     * 渲染组件
     * @return string
     */
    public function render()
    {
        $data = [
            'nodeName' => 'app-file',
        ];
        if ($this->url) {
            $data['upload'] = $this->url;
        }
        if ($this->fileUrl) {
            $data['fileUrl'] = $this->fileUrl;
        }
        if ($this->type) {
            $data['type'] = $this->type;
        }
        if ($this->model) {
            $data['vModel:value'] = $this->getModelField();
        }
        return $data;
    }

}
