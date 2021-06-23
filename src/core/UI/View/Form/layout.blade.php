@foreach($items as $item)
    @foreach ($item['group'] as $group)
        <div group-{{$group['name']}}="{{$group['value']}}" hidden="hidden">
            @endforeach
            @if($item['layout'])
                <div class="mb-4">
                    <label
                        class="form-label block mb-2 text-gray-700 {{$item['must'] ? 'required' : ''}}">{!! $item['name'] !!}
                    </label>
                    <div>
                        @endif
                        @if($item['prompt'] ||  $item['help'])
                            <div class="flex items-center">
                                <div class="flex-grow">
                                    {!! $item['html'] !!}
                                </div>
                                @if($item['prompt'])
                                    <div class="mt-2 mb-2 ml-3"><span data-js="show-tooltip"
                                                                      data-title="{{$item['prompt']}}"
                                                                      class="block cursor-pointer text-center text-white w-4 h-4 text-xs rounded-full bg-gray-600">?</span>
                                    </div>
                                @endif
                                @if($item['help'])
                                    <div class="text-gray-500 pt-2 pb-2 ml-3">{{$item['help']}}</div>
                                @endif
                            </div>
                        @else
                            {!! $item['html'] !!}
                        @endif
                        @if($item['helpLine'])
                            <p class="text-gray-500 mt-1">{{$item['helpLine']}}</p>
                        @endif
                        @if($item['layout'])
                    </div>
                </div>
            @endif
            @foreach ($item['group'] as $group)
        </div>
    @endforeach
@endforeach
