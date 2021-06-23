<?php

namespace Duxravel\Core\Service;

use Illuminate\Support\Facades\Blade;

/**
 * 应用扩展接口
 */
class App
{
    public function extend()
    {

        /**
         * 模板组件
         */
        Blade::component('app-loading', \Duxravel\Core\UI\Components\Loading::class);
        Blade::component('app-nodata', \Duxravel\Core\UI\Components\NoData::class);
        Blade::component('app-trend', \Duxravel\Core\UI\Components\Trend::class);

        /**
         * 分页标签
         */
        Blade::directive('paginate', function ($label) {
            return '<?php echo $pageData ? $pageData->links('.$label.') : "" ?>';
        });
    }
}

