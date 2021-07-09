@foreach($items as $item)
    @php
        $id = request()->get($key);
        $expand = in_array($id, $item->descendants()->pluck($key)->toArray());
        $array = [];
        foreach ($params as $k => $v) {
            $array[$k] = \Duxravel\Core\UI\Tools::parsingArrData($item, $v, true);
        }
    @endphp
    <li x-cloak
        x-data="{expand: {{$expand ? 1 : 0}}, last: {{ $loop->last ? 1 : 0 }}, sub: {{$item->children->count()}} }">
        <a class="flex gap-2 items-center block truncate hover:text-blue-900 {{$level ? 'pl-' . ($level * 4) : 'pl-2'}} {{$id == $item[$key] ? 'text-blue-900' : ''}}"
           href="#"
           :class="{'bg-white': expand && sub}"
           @click="sub ? expand = !expand : window.location.href='{{route($route, $array)}}'">
            @if($level)
                <div x-show="!last &&  (!expand || !sub)" class="flex-none">
                    <svg xmlns="http://www.w3.org/2000/svg" height="35" viewBox="0 0 9.297 35">
                        <path id="Link1"
                              d="M0,35V33.856H.547V35Zm0-1.144a.273.273,0,0,1,.547,0Zm0-2.288V29.279H.547v2.289a.273.273,0,0,1-.547,0Zm0-2.289a.273.273,0,0,1,.547,0Zm0-2.288V24.7H.547v2.289a.273.273,0,0,1-.547,0ZM0,24.7a.273.273,0,1,1,.547,0Zm0-2.289V20.125H.547v2.288a.273.273,0,1,1-.547,0Zm0-2.288a.273.273,0,1,1,.547,0Zm0-2.289V16.693H0V15.649H.547v.77h.82a.273.273,0,0,1,0,.547H.547v.871a.273.273,0,1,1-.547,0Zm7.93-.871v-.547H9.023a.273.273,0,1,1,0,.547Zm-.273-.273a.273.273,0,0,1,.273-.274v.547A.273.273,0,0,1,7.656,16.693Zm-4.1.273v-.547H5.742a.273.273,0,0,1,0,.547Zm-.273-.273a.273.273,0,0,1,.273-.274v.547A.273.273,0,0,1,3.281,16.693ZM0,15.649a.273.273,0,1,1,.547,0Zm0-2.086V11.476H.547v2.087a.273.273,0,0,1-.547,0Zm0-2.087a.273.273,0,0,1,.547,0ZM0,9.389V7.3H.547V9.389a.273.273,0,0,1-.547,0ZM0,7.3a.273.273,0,1,1,.547,0ZM0,5.216V3.13H.547V5.216a.273.273,0,1,1-.547,0ZM0,3.13a.273.273,0,1,1,.547,0ZM0,1.043V0H.547V1.043a.273.273,0,0,1-.547,0Z"/>
                    </svg>
                </div>
                <div x-show="last || ( sub && expand)" class="flex-none h-8">
                    <svg v-show="" xmlns="http://www.w3.org/2000/svg" height="16.966" viewBox="0 0 9.297 16.966">
                        <path d="M7.93,16.966v-.547H9.023a.274.274,0,0,1,0,.547Zm-.274-.274a.273.273,0,0,1,.274-.274v.547A.273.273,0,0,1,7.656,16.692Zm-4.1.274v-.547H5.742a.274.274,0,1,1,0,.547Zm-.274-.274a.273.273,0,0,1,.274-.274v.547A.273.273,0,0,1,3.281,16.692Zm-3.008.274A.273.273,0,0,1,0,16.692V15.649H.547v.77h.82a.274.274,0,1,1,0,.547ZM0,15.649a.274.274,0,0,1,.547,0Zm0-2.087V11.476H.547v2.086a.274.274,0,0,1-.547,0Zm0-2.086a.274.274,0,1,1,.547,0ZM0,9.39V7.3H.547V9.39A.274.274,0,1,1,0,9.39ZM0,7.3a.274.274,0,0,1,.547,0ZM0,5.216V3.13H.547V5.216a.274.274,0,1,1-.547,0ZM0,3.13a.274.274,0,1,1,.547,0ZM0,1.043V0H.547V1.043a.274.274,0,0,1-.547,0Z"/>
                    </svg>
                </div>
            @endif
            <div class="flex-none">
                <svg x-show="!sub" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                     stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16"/>
                </svg>
                <svg x-show="!expand && sub" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"/>
                </svg>
                <svg x-show="expand && sub" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9a2 2 0 00-2 2v5a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <div class="flex-grow py-2">{{$item[$name]}}</div>
        </a>
        @if($item->children->count())
            <div x-show="expand" class="bg-gray-500 rounded bg-opacity-5">
                <ul x-data="{expand: false}">
                    @include('vendor.duxphp.duxravel-app.src.core.UI.View.Widget.tree-list-inner', ['items' => $item->children, 'level' => $level + 1])
                </ul>
            </div>
        @endif
    </li>
@endforeach
