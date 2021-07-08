@foreach($list as $item)
    <div
        x-data="{expand: false, num: $refs.group.children.length}"
        data-tr="{{$item['id']}}"
        data-json="{{$item['json']}}"
    >
        <div
            class="flex border-gray-300 items-center flex-nowrap box-border">
            @if($batch)
                <div data-td class="flex-none flex items-center justify-start px-3 py-4" style="width: 50px;">
                    <input class="form-checkbox" data-check type="checkbox" value="{{$item['key']}}">
                </div>
            @endif

            @if($sortable)
                <div class="w-6 py-4">
                    <div class="drag select-none cursor-move w-6 justify-end">
                        <svg class="w-4 h-4 stroke-current text-gray-100" viewBox="0 0 1024 1024" version="1.1"
                             xmlns="http://www.w3.org/2000/svg" p-id="2396" width="32" height="32">
                            <path
                                d="M256 192c0 35.328 30.08 64 67.2 64 37.12 0 67.2-28.672 67.2-64s-30.08-64-67.2-64C286.08 128 256 156.672 256 192zM569.6 192c0 35.328 30.08 64 67.2 64C673.92 256 704 227.328 704 192s-30.08-64-67.2-64c-37.12 0-67.2 28.672-67.2 64zM256 512c0 35.328 30.08 64 67.2 64 37.12 0 67.2-28.672 67.2-64s-30.08-64-67.2-64C286.08 448 256 476.672 256 512zM569.6 512c0 35.328 30.08 64 67.2 64C673.92 576 704 547.328 704 512s-30.08-64-67.2-64c-37.12 0-67.2 28.672-67.2 64zM256 832c0 35.328 30.08 64 67.2 64 37.12 0 67.2-28.672 67.2-64s-30.08-64-67.2-64C286.08 768 256 796.672 256 832zM569.6 832c0 35.328 30.08 64 67.2 64 37.12 0 67.2-28.672 67.2-64s-30.08-64-67.2-64c-37.12 0-67.2 28.672-67.2 64z"
                                fill="#000000" p-id="2397"></path>
                        </svg>
                    </div>
                </div>
            @endif

            @foreach ($item['column'] as $key => $vo)

                <div data-td
                     class="{{$vo->width ? 'flex-none' : 'flex-grow'}} {{$vo->drag ? 'select-none ' : '  px-3'}} whitespace-nowrap flex items-center py-4 {!! $vo->class !!}"
                     style="{!! $vo->style !!} {{ $vo->width ? 'width:' . $vo->width . 'px' : ''}}"
                     {!! $vo->attr !!}
                     data-label="{{$thead[$key]->name}}"
                     x-on:click="expand = !expand"
                >
                    @if($vo->drag)
                        <div class="w-6">
                            <svg x-show="!expand && num" class="w-4 h-4 fill-current" viewBox="0 0 1024 1024"
                                 version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" p-id="2668" width="16" height="16">
                                <path d="M682.666667 512l-298.666667-298.666667L384 810.666667z"
                                      p-id="2669"></path>
                            </svg>
                            <svg x-show="expand && num" class="w-4 h-4 fill-current" viewBox="0 0 1024 1024"
                                 version="1.1"
                                 xmlns="http://www.w3.org/2000/svg" p-id="2383" width="16" height="16">
                                <path d="M225.57465256 325.66086286h572.85069488l-286.42534744 343.71041693z"
                                      p-id="2384"></path>
                            </svg>
                        </div>
                    @endif
                    {!! $vo->data !== null && $vo->data !== '' ? $vo->data : '-' !!}
                </div>
            @endforeach
        </div>
        <div
            class="sortable-group"
            x-cloak
            x-ref="group"
            x-show="expand || !num"
            @notify="num = $refs.group.children.length; expand = true"
            data-parent="{{$item['id']}}"
        >
            @if($item['children'])
                @include('vendor.duxphp.duxravel-app.src.core.UI.View.table-list-tree', ['list' => $item['children']])
            @endif
        </div>
    </div>

@endforeach
