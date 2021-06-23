@if($tbody)
    <thead>
    <tr class="">
        @if($batch)
            <th width="50">
                <input class="form-check-input align-middle" data-check-all type="checkbox">
            </th>
        @endif
        @foreach ($thead as $vo)
            <th
                {{$vo->attr}}
                class="{{$vo->class}}"
                style="{!! $vo->style !!}"
            >{!! str_replace("\n", '<br>', $vo->name) !!}</th>
        @endforeach
    </tr>
    </thead>
    <tbody>

    @foreach ($tbody as $row)
        <tr data-json="{{$row['json']}}"
            @if($tree)
            data-tt-id="{{$row['key']}}"
            data-tt-parent-id="{{$row['data'][$tree]}}"
            @endif
        >
            @if($batch)
                <td class=" whitespace-nowrap">
                    <input class="form-check-input align-middle" data-check type="checkbox"
                           value="{{$row['key']}}">
                </td>
            @endif
            @foreach ($row['column'] as $key => $vo)
                @if ($vo->colspan !== 0)
                    <td class=" whitespace-nowrap {!! $vo->class !!}"
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
