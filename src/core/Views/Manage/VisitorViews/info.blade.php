<div class="p-6"><div class="chart-tab grid grid-cols-4 border border-gray-300 text-center">
    <a href="javascript:;" data-day="7" class="py-4  border-r border-gray-300 active ring ring-blue-900 bg-blue-200 bg-opacity-50">
        <div class="card-body">
            <p>7天</p>
        </div>
    </a>
    <a href="javascript:;" data-day="30" class="py-4 border-r border-gray-300">
        <div class="card-body">
            <p>30天</p>
        </div>
    </a>
    <a href="javascript:;" data-day="90" class="py-4 border-r border-gray-300">
        <div class="card-body">
            <p>90天</p>
        </div>
    </a>
    <a href="javascript:;" data-day="365" class="py-4">
        <div class="card-body">
            <p>1年内</p>
        </div>
    </a>
</div>
    <div class="mt-6" id="app-chart"></div>
</div>
{!! $appChart !!}
<script>
    Do('chart', function () {
        let now = new Date();
        window['chart-app-chart'].zoomX(
            new Date(now.getTime() - 7 * 24 * 3600 * 1000).getTime(),
            now.getTime()
        )
        $('.chart-tab').on('click', '[data-day]', function () {
            $('.chart-tab').find('.active').removeClass('active ring ring-blue-900 bg-blue-200 bg-opacity-50')
            $(this).addClass('active ring ring-blue-900 bg-blue-200 bg-opacity-50')
            let day = parseInt($(this).data('day'))
            window['chart-app-chart'].zoomX(
                new Date(now.getTime() - day * 24 * 3600 * 1000).getTime(),
                now.getTime()
            )
        });
    });
</script>
