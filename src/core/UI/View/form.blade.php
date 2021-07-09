<div class="lg:flex">
    @foreach($sideLeftHtml as $vo)
        <div class="lg:flex-none hidden lg:block">
            {!! $vo !!}
        </div>
    @endforeach

    <div class="lg:flex-grow lg:w-44">
        <form method="{{$method}}" action="{{$action}}" {!! implode(' ', $attr) !!} data-js="form-bind" class="p-5">
            @if($title && !$dialog)
                <div class="mb-3">
                    <div class="flex items-center">
                        <div class="flex-grow">
                            @if($back)
                                <a href="javascript:window.history.back();"
                                   class="text-xs items-center text-gray-500 hidden lg:flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon w-5 h-5" viewBox="0 0 24 24"
                                         stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round"
                                         stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                        <line x1="5" y1="12" x2="11" y2="18"/>
                                        <line x1="5" y1="12" x2="11" y2="6"/>
                                    </svg>
                                    返回
                                </a>
                            @endif
                            <div class="text-lg lg:text-xl">
                                {{$title}}
                            </div>
                        </div>
                        <div class="flex-none items-center hidden lg:flex gap-2">
                            <button class="btn" type="reset">
                                重置
                            </button>
                            <button class="btn-blue" type="submit">
                                提交
                            </button>
                        </div>
                    </div>
                </div>
            @endif

            {!! $formHtml !!}

            @if(!$dialog)
                <div class="my-3 flex items-center flex-col lg:flex-row justify-end gap-2">
                    <button type="reset" class="btn w-full lg:w-auto hidden lg:block">重置</button>
                    <button type="submit" class="btn-blue w-full lg:w-auto">提交</button>
                </div>
            @else
                <div class="mt-3 flex items-center justify-end gap-2 flex-col-reverse lg:flex-row ">
                    <button type="button" class="btn w-full lg:w-auto" modal-close>取消</button>
                    <button type="submit" class="btn-blue  w-full lg:w-auto">提交</button>
                </div>
            @endif
        </form>
    </div>


    @foreach($sideRightHtml as $vo)
        <div class="lg:flex-none hidden lg:block">
            {!! $vo !!}
        </div>
    @endforeach

</div>


<script>
    Do('base', function () {
        @foreach($script as $vo)
            {!! $vo !!}
        @endforeach
    });
</script>