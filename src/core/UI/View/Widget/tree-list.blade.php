<div class="w-44">
    <div class="flex-none bottom-0 top-14 fixed bottom-0 overflow-auto w-44  bg-white fixed px-2 pt-1 border-gray-300 border-solid border-r ">
        <div class="text-xs text-gray-500 py-3">{{$title}}</div>
        <div>
            <ul>
                @include('vendor.duxphp.duxravel-app.src.core.UI.View.Widget.tree-list-inner', ['items' => $data, 'level' => 0])
            </ul>
        </div>
    </div>
</div>