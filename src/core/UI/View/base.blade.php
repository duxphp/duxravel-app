<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{config('app.name')}} {{config('theme.title')}}</title>

    <meta name="msapplication-TileColor" content="#206bc4"/>
    <meta name="theme-color" content="#206bc4"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="robots" content="noindex,nofollow,noarchive"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ public_path('static/system/css/base.min.css') }}"/>
    <link rel="stylesheet" href="{{ public_path('static/system/css/fontawesome.min.css') }}"/>
    <script src="{{ public_path('static/system/js/jquery.min.js') }}"></script>
    <script src="{{ public_path('static/system/js/app.min.js') }}"></script>

</head>
<body class="text-sm  bg-gray-200 lg:h-screen overflow-x-hidden overflow-auto">
<div class="lg:flex">
    <div class="lg:hidden h-14" x-data='{active: {{$menuActive}}, items: menuList, open: false}'>
        <header class="bg-white h-14 shadow flex fixed w-full items-center justify-center px-4 z-10">
            <div class="flex-none">
                <a  href="{{route('admin.index')}}" class="h-10 w-10 flex items-center justify-center">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         x="0px"
                         y="0px"
                         viewBox="0 0 12 16" xml:space="preserve" class="h-6 w-6 fill-current text-blue-900">
                        <g>
                            <polygon
                                points="6.66,12.04 6.66,8.92 3.43,8.92 3.43,3.96 5.34,3.96 5.34,7.08 8.57,7.08 8.57,12.04 	"/>
                            <polygon
                                points="3.43,15.17 3.43,10.21 5.34,10.21 5.34,13.33 9.9,13.33 9.9,0.83 11.81,0.83 11.81,15.17 	"/>
                            <polygon
                                points="0.19,15.17 0.19,0.83 8.57,0.83 8.57,5.79 6.66,5.79 6.66,2.67 2.1,2.67 2.1,15.17 	"/>
                        </g>
                    </svg>
                </a>

            </div>
            <div class="flex-grow text-center text-lg text-gray-800">{{config('app.name')}}</div>
            <div class="flex-none">
                <button type="button"
                        class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                        x-on:click="open = true">
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </header>
        <div class="fixed z-10 bg-white m-2  left-0 right-0 shadow bg-white border rounded-md border-solid border-gray-300" x-cloak x-show="open">
            <div class="flex p-2">
                <div class="flex-none">
                    <a  href="{{route('admin.index')}}" class="h-10 w-10 flex items-center justify-center">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             x="0px"
                             y="0px"
                             viewBox="0 0 12 16" xml:space="preserve" class="h-6 w-6 fill-current text-blue-900">
                        <g>
                            <polygon
                                points="6.66,12.04 6.66,8.92 3.43,8.92 3.43,3.96 5.34,3.96 5.34,7.08 8.57,7.08 8.57,12.04 	"/>
                            <polygon
                                points="3.43,15.17 3.43,10.21 5.34,10.21 5.34,13.33 9.9,13.33 9.9,0.83 11.81,0.83 11.81,15.17 	"/>
                            <polygon
                                points="0.19,15.17 0.19,0.83 8.57,0.83 8.57,5.79 6.66,5.79 6.66,2.67 2.1,2.67 2.1,15.17 	"/>
                        </g>
                        </svg>
                    </a>
                </div>
                <div class="flex-grow text-center text-lg text-gray-800">{{config('app.name')}}</div>
                <div class="flex-none">
                    <button type="button"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                            x-on:click="open = false"
                    >
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="pb-4 overflow-auto max-h-64">
                <ul>
                    <template x-for="(item, index) in items" :key="index">
                        <li class="px-4" x-show="!item.hidden || index === active">
                            <div class="flex items-center space-x-2 p-2 rounded"
                                 @click="item.url ? window.location.href = item.url : (active === index ? active = -1 : active = index)"
                                 :class="{ 'bg-blue-900 text-white': active === index}"
                            >
                                <span class="flex-none w-5 mr-1 fill-current  flex items-center justify-center"
                                      x-html="item.icon"></span>
                                <span class="flex-grow" x-text="item.name"></span>
                            </div>
                            <template x-if="item.menu && item.menu.length">
                                <div class="pt-2" x-show="index === active">
                                    <template x-for="(parent, parentIndex) in item.menu" :key="parentIndex">
                                        <div>
                                            <div class="text-xs text-gray-500 py-2 px-2 pl-8"
                                                 x-text="parent.name"></div>
                                            <template x-if="parent.menu.length">
                                                <ul>
                                                    <template x-for="(sub, subIndex) in parent.menu" :key="subIndex">
                                                        <li>
                                                            <a :href="sub.url"
                                                               class="text-gray-800 hover:text-blue-900 block p-2 pl-8 rounded"
                                                               :class="{'bg-blue-200 text-blue-900' : sub.cur, 'text-gray-800 hover:text-blue-900' : !sub.cur}"
                                                               x-text="sub.name"></a>
                                                        </li>
                                                    </template>
                                                </ul>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </template>
                        </li>
                    </template>
                </ul>
            </div>
        </div>
    </div>
    <aside class="z-10 hidden lg:flex" x-data='{active: {{$menuActive}}, items: menuList}'>
        <div class="flex-none w-24 bg-gray-800 fixed left-0 top-0 bottom-0 text-white ">
            <div class="flex flex-col items-center justify-center">
                <div class="rounded-full h-10 w-10 flex items-center justify-center bg-white mt-5">
                    <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                         x="0px"
                         y="0px"
                         viewBox="0 0 12 16" xml:space="preserve" class="h-6 w-6 fill-current text-blue-900">
                                <g>
                                    <polygon
                                        points="6.66,12.04 6.66,8.92 3.43,8.92 3.43,3.96 5.34,3.96 5.34,7.08 8.57,7.08 8.57,12.04 	"/>
                                    <polygon
                                        points="3.43,15.17 3.43,10.21 5.34,10.21 5.34,13.33 9.9,13.33 9.9,0.83 11.81,0.83 11.81,15.17 	"/>
                                    <polygon
                                        points="0.19,15.17 0.19,0.83 8.57,0.83 8.57,5.79 6.66,5.79 6.66,2.67 2.1,2.67 2.1,15.17 	"/>
                                </g>
                                </svg>
                </div>
                <div class="text-xs text-gray-400 mt-2">后台管理</div>
            </div>
            <div class="text-gray-400 p-2 mt-3">
                <ul>
                    <template x-for="(item, index) in items" :key="index">
                        <li class="mb-2" x-show="!item.hidden || index === active">
                            <div
                                @click="item.url ? window.location.href=item.url : active = index"
                                class="cursor-pointer block rounded-sm  py-1.5 text-center flex items-center justify-center text-gray-300"
                                :class="{ 'bg-blue-900 text-white': active === index, 'hover:text-white hover:bg-gray-700': active !== index }"
                            >
                            <span class="w-5 mr-1 fill-current  flex items-center justify-center"
                                  x-html="item.icon"></span>
                                <span x-text="item.name"></span>
                            </div>
                        </li>
                    </template>
                </ul>

            </div>
        </div>
        <div class="flex-none">
            <template x-for="(item, index) in items" :key="index">
                <div x-show="active == index">
                    <template x-if="item.menu && item.menu.length">
                        <div class="flex-none w-32">
                            <div
                                class="w-32 top-14 bg-white h-full fixed left-24 px-2 pt-1 border-gray-300 border-solid border-r">
                                <template x-for="(parent, parentIndex) in item.menu" :key="parentIndex">
                                    <div>
                                        <div class="text-xs text-gray-500 py-3 px-2" x-text="parent.name"></div>
                                        <template x-if="parent.menu.length">
                                            <ul>
                                                <template x-for="(sub, subIndex) in parent.menu" :key="subIndex">
                                                    <li>
                                                        <a :href="sub.url"
                                                           class="text-gray-800 hover:text-blue-900 block p-2 rounded"
                                                           :class="{'bg-blue-200 text-blue-900' : sub.cur, 'text-gray-800 hover:text-blue-900' : !sub.cur}"
                                                           x-text="sub.name"></a>
                                                    </li>
                                                </template>
                                            </ul>
                                        </template>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
        </div>
    </aside>
    <main class="lg:flex-grow lg:ml-24 lg:h-full">
        <div class="h-14 hidden lg:flex">
            <header class="bg-white shadow p-2 flex items-center z-10 fixed top-0 right-0 left-24 h-14">
                <div
                    class="flex-none flex space-x-2 pl-2 items-center text-gray-500 hover:text-gray-900 cursor-pointer select-none"
                    data-js="dialog-open" data-layout="false" data-url="{{route('admin.search')}}" data-title="搜索引擎">
                    <div>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <div class="text-sm ">内容搜索...</div>
                </div>
                <div class="flex-grow">

                </div>
                <div class="flex-none relative mr-2 cursor-pointer select-none" x-cloak x-data="{open: false}">
                    <div class="flex" @click="open = !open">
                        <div
                            class="flex-none mr-2 bg-gray-400 text-gray-700 rounded flex justify-center items-center w-9 h-9">
                            A
                        </div>
                        <div class="flex-grow">
                            <div class="text-sm">
                                管理员
                            </div>
                            <div class="text-xs text-gray-500">
                                admin
                            </div>
                        </div>
                    </div>
                    <ul x-show="open" @click.away="open = false"
                        class="shadow absolute right-0 w-40 pt-1 pb-1 rounded-sm bg-white">
                        <li>
                            <a target="_blank" href="{{route('web.index')}}" class="block p-2 hover:bg-gray-200">返回首页</a>
                        </li>
                        {{--<li>
                            <a href="" class="block p-2 hover:bg-gray-200">个人设置</a>
                        </li>--}}
                        <li>
                            <a href="{{route('admin.login.logout')}}" class="block p-2 hover:bg-gray-200">退出登录</a>
                        </li>
                    </ul>
                </div>
            </header>
        </div>
        <div>
            @include($layout)
        </div>
    </main>
</div>
</body>
<script src="https://cdn.jsdelivr.net/gh/alpinejs/alpine@v2.x.x/dist/alpine.min.js" defer></script>
<script>
    window.menuList = @json($menuList);

    Do('base', function () {
        base.init()
    });

</script>
</html>
