@if($tbody)
    <div data-head class=" bg-gray-200 flex items-center flex-nowrap text-gray-600 box-border">
        @if($batch)
            <div
                class="flex-none flex items-center justify-start px-3 py-3"
                style="width: 50px;">
                <input class="form-checkbox" data-check-all type="checkbox">
            </div>
        @endif

        @foreach ($thead as $vo)
            <div {{$vo->attr}}
                 class="{{$vo->width ? 'flex-none' : 'flex-grow'}} whitespace-nowrap   px-3 py-3 {{$vo->class}}"
                 style=" {{ $vo->width ? 'width:' . $vo->width . 'px' : ''}}">
                {!! str_replace("\n", '<br>', $vo->name) !!}
            </div>
        @endforeach

    </div>

    <div data-tbody sortable-layout class="mt-2 sortable-group">
        @include('vendor.duxphp.duxravel-app.src.core.UI.View.table-list-tree', ['list' => $tbody])
    </div>
@else
    <div class="border border-gray-300 rounded p-6">
        <x-app-nodata/>
    </div>
@endif

