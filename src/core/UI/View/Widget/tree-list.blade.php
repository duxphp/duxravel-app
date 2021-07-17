<div class="w-44 flex flex-col bg-white rounded shadow">
    <div class="p-4 flex-none border-b border-gray-300">{{$title}}</div>
    <div class="flex-grow overflow-y-hidden">
        <ul>
            @include('vendor.duxphp.duxravel-app.src.core.UI.View.Widget.tree-list-inner', ['items' => $data, 'level' => 0])
        </ul>
    </div>
</div>