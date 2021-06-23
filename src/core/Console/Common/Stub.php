<?php

namespace Duxravel\Core\Console\Common;

use Illuminate\Console\Command;

class Stub extends Command
{
    public function generatorDir($path)
    {
        $path = base_path('/modules/' . $path);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
    }

    public function generatorFile($file, $tpl = '', $data = [])
    {
        $file = base_path('/modules/' . $file);
        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($path, 0777, true);
        }
        $content = file_get_contents($tpl);
        foreach ($data as $key => $vo) {
            $content = str_replace('{{' . $key . '}}', $vo, $content);
        }
        file_put_contents($file, $content);
    }

    public function getAppName($name)
    {
        $name = $this->ask('请输入'.$name.'(英文)');
        if (!preg_match('/[a-zA-Z]/', $name, $match)) {
            $this->error($name.'只支持英文字符!');
            return $this->getAppName($name);
        }
        return $name;
    }
}
