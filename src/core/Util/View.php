<?php

namespace Duxravel\Core\Util;

use Jenssegers\Agent\Agent;


class View
{

    private string $tpl;
    private array $data;

    /**
     * @param string $tpl
     * @param array  $data
     */
    public function __construct(string $tpl = '', array $data = [])
    {
        $this->tpl = $tpl;
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function render()
    {
        $assign = $this->data;
        $parsing = app_parsing();

        if (!$this->tpl) {
            $view = [$parsing['app'], 'View', $parsing['layer'], $parsing['module'], $parsing['action']];
            $this->tpl = implode('.', $view);
        }
        $assign['manage'] = strtolower($parsing['layer']);
        return view( $this->tpl, $assign);
    }

    /**
     * @return mixed
     */
    public static function manage()
    {
        $manifest = json_decode(file_get_contents(public_path('static/manage-manifest.json')), true);

        $mainJs = $manifest['src/main.js']['file'];
        $mainCss = $manifest['src/main.js']['css'][0];


        $app = strtolower(app_parsing('layer'));

        $sideImages = [];
        $files = glob(public_path('/images/login-side*.png'));
        foreach ($files as $file) {
            $sideImages[] = str_replace(public_path(), '', $file);
        }

        return response()->view('vendor.duxphp.duxravel-app.src.core.Views.manage', [
            'mainJs' => $mainJs,
            'mainCss' => $mainCss,
            'sideImages' => $sideImages,
            'info' => config($app . '.info') ?: []
        ]);
    }
}
