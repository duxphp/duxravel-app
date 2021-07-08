@if($tbody)
    <thead data-head>
    <tr class="">
        @if($batch)
            <th width="50">
                <input class="form-checkbox" data-check-all type="checkbox">
            </th>
        @endif
        @foreach ($thead as $vo)
            <th
                    {{$vo->attr}}
                    class="{{$vo->class}}"
                    style="{!! $vo->style !!} "
                    width="{{$vo->width}}"
            >{!! str_replace("\n", '<br>', $vo->name) !!}</th>
        @endforeach
    </tr>
    </thead>
    <tbody data-tbody>

    @foreach ($tbody as $row)
        <tr data-json="{{$row['json']}}">
            @if($batch)
                <td data-td class=" whitespace-nowrap">
                    <input class="form-checkbox" data-check type="checkbox"
                           value="{{$row['key']}}">
                </td>
            @endif
            @foreach ($row['column'] as $key => $vo)
                @if ($vo->colspan !== 0)
                    <td data-td class=" whitespace-nowrap {!! $vo->class !!}"
                        style="{!! $vo->style !!}" {!! $vo->attr !!}
                        data-label="{{$thead[$key]->name}}"
                            {{$vo->colspan ? 'colspan='.$vo->colspan : ''}}
                    >
                        {!! $vo->data !== null && $vo->data !== '' ? $vo->data : '-' !!}
                    </td>
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
@else
    <div class="border border-gray-300 rounded p-6">
        <x-app-nodata/>
    </div>
@endif
