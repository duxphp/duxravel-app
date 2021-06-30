<div class="m-5 ">
    @if($title || $actions)
    <div class="flex items-center  pb-4">
        @if($title)
        <div class="flex-grow">
            <div class="text-xl">{{$title}}</div>
        </div>
        @endif
        @if ($actions)
        <div class="flex-none flex gap-2">
            @foreach ($actions as $vo)
            <div>{!! $vo !!}</div>
            @endforeach
        </div>
        @endif
    </div>
    @endif

    @if($headerHtml)
        @foreach($headerHtml as $vo)
        <div class="pb-4">
        {!! $vo !!}
        </div>
        @endforeach
    @endif

    <div class="bg-white rounded px-4 {{!$dialog ? 'shadow' : ''}}">

        @if($quick || $actions || $filterType)
        <form method="get" data-filter>
            @if($filterParams || $quick || $actions)
            <div x-cloak x-data="{collapse: {{$filterStatus ? 'true' : 'false'}} }">

            @if ($filterType)
            <div class="border-b border-gray-300 flex gap-4 flex-nowrap overflow-x-auto">
                {!! $filterType !!}
            </div>
            @endif

                @foreach($filterParams as $vo)
                <input type="hidden" name="{{$vo['name']}}" value="{{$vo['value']}}">
                @endforeach
                @if ($quick)
                <div class="flex flex-wrap flex-col lg:flex-row gap-4 pt-4">
                    @foreach ($quick as $vo)
                    {!! $vo !!}
                    @endforeach

                    @if ($filter)
                    <div>
                        <button type="button" class="btn-outline-blue  w-full flex justify-center" data-bs-toggle="collapse" data-bs-target="#filter-sub" aria-expanded="false" aria-label="筛选" @click="collapse = !collapse">筛选
                        </button>
                    </div>
                    @endif
                    <div>
                        <button type="submit" class=" btn-blue w-full flex justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" style="width: 1.25rem; height: 1.25rem" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <circle cx="10" cy="10" r="7" />
                                <line x1="21" y1="21" x2="15" y2="15" />
                            </svg>

                        </button>
                    </div>
                </div>
                @endif

                <div class=" {{ $filterStatus ? 'show' : '' }}" x-show="collapse">
                    <div class="flex flex-wrap flex-col lg:flex-row gap-4 pt-4">
                        @foreach ($filter as $vo)
                        {!! $vo !!}
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </form>
        @endif

        <div data-js="table-bind" data-ajax="{{$ajax}}" data-tree="{{$tree}}" class="py-4">
            @if($toolsHtml)
            @foreach($toolsHtml as $vo)
            {!! $vo !!}
            @endforeach
            @endif
            <div class="overflow-x-auto">
                <table class="table-box {{$class}}" style="{!! $style !!}" {!! $attr !!}
               data-table>
            @include('vendor.duxphp.duxravel-app.src.core.UI.View.table-ajax') </table>
            </div>
            @if($batch || $pages)
            <div class="card-footer flex items-center pt-2">
                <div class="flex flex-grow space-x-2" data-batch>
                    @if($batch)
                    <div class="flex items-center ">
                        <input class="form-check-input align-middle" data-check-all type="checkbox">
                    </div>
                    @foreach($batch as $vo)
                    <div>
                        {!! $vo !!}
                    </div>
                    @endforeach
                    @endif
                </div>
                <div class="flex-none">
                    @if($ajax)
                    <ul data-pagination class="pagination m-0"></ul>
                    @else
                    {!! $pages !!}
                    @endif
                </div>
            </div>
            @endif

        </div>

        @if($footerHtml)
        @foreach($footerHtml as $vo)
        {!! $vo !!}
        @endforeach
        @endif

    </div>

</div>
