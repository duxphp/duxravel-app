<table class="table-box">
    <thead>
    <tr>
        <th class="">接口</th>
        <th class="">类型</th>
        <th class="text-right">时间</th>
    </tr>
    </thead>
    <tbody class="">
    @foreach($apiList as $vo)
        <tr>
            <td class=" whitespace-nowrap ">
                {{$vo->desc}}
                <div class="text-gray-500">{{$vo->name}}</div>
            </td>
            <td class=" whitespace-nowrap ">
                {{$vo->method}}
            </td>
            <td class=" whitespace-nowrap  text-right">
                {{$vo->create_time}}
                <div class="text-gray-500">{{$vo->time}}s</div>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
