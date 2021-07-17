<!doctype html>
<html>
<head>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{config('app.name')}} - {{config('theme.title')}}</title>

    <meta name="msapplication-TileColor" content="#206bc4"/>
    <meta name="theme-color" content="#206bc4"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="robots" content="noindex,nofollow,noarchive"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="/static/system/css/base.min.css"/>
    <link rel="stylesheet" href="/static/system/css/fontawesome.min.css"/>
    <script src="/static/system/js/jquery.min.js"></script>
    <script src="/static/system/js/app.min.js" data-manage="{{ $manage }}"></script>
    <script defer src="https://lib.baomitu.com/alpinejs/3.2.1/cdn.min.js"></script>

</head>
<body class="text-sm  bg-gray-200 overflow-x-hidden overflow-auto">
<div class="lg:flex"
     x-data='manageInit'>
    <div class="lg:flex-none" x-bind="bindAside">
        <div class="lg:hidden h-14">
            <header class="bg-white h-14 shadow flex fixed w-full items-center justify-center px-4 z-10">
                <div class="flex-none">
                    <a href="{{route('admin.index')}}" class="h-10 w-10 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.97 197.7"
                             class="h-6 w-6 fill-current text-blue-900">
                            <path
                                    d="M170,94.52l-35.9-20.73-24.34,14,11.62,6.71a5,5,0,0,1,0,8.66L32.5,154.52a5,5,0,0,1-7.5-4.33V99.61a6.44,6.44,0,0,1,0-1.52V47.51a5,5,0,0,1,7.5-4.33l35,20.23,24.32-14L7.5.68A5,5,0,0,0,0,5V192.69A5,5,0,0,0,7.5,197L170,103.18A5,5,0,0,0,170,94.52Z"/>
                            <path
                                    d="M32.93,103.18l35.9,20.73,24.34-14-11.62-6.71a5,5,0,0,1,0-8.66l88.92-51.34a5,5,0,0,1,7.5,4.33V98.09a6.44,6.44,0,0,1,0,1.52v50.58a5,5,0,0,1-7.5,4.33l-35-20.23-24.32,14L195.47,197a5,5,0,0,0,7.5-4.33V5a5,5,0,0,0-7.5-4.33L32.93,94.52A5,5,0,0,0,32.93,103.18Z"/>
                        </svg>
                    </a>
                </div>
                <div class="flex-grow text-center text-lg text-gray-800">{{config('app.name')}}</div>
                <div class="flex-none">
                    <button type="button"
                            class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                            x-on:click="menuOpen = true">
                        <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                             stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </header>
            <div
                    class="fixed z-10 bg-white m-2  left-0 right-0 shadow bg-white border rounded-md border-solid border-gray-300"
                    x-cloak x-show="menuOpen">
                <div class="flex p-2">
                    <div class="flex-none">
                        <a href="{{route('admin.index')}}" class="h-10 w-10 flex items-center justify-center">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.97 197.7"
                                 class="h-6 w-6 fill-current text-blue-900">
                                <path
                                        d="M170,94.52l-35.9-20.73-24.34,14,11.62,6.71a5,5,0,0,1,0,8.66L32.5,154.52a5,5,0,0,1-7.5-4.33V99.61a6.44,6.44,0,0,1,0-1.52V47.51a5,5,0,0,1,7.5-4.33l35,20.23,24.32-14L7.5.68A5,5,0,0,0,0,5V192.69A5,5,0,0,0,7.5,197L170,103.18A5,5,0,0,0,170,94.52Z"/>
                                <path
                                        d="M32.93,103.18l35.9,20.73,24.34-14-11.62-6.71a5,5,0,0,1,0-8.66l88.92-51.34a5,5,0,0,1,7.5,4.33V98.09a6.44,6.44,0,0,1,0,1.52v50.58a5,5,0,0,1-7.5,4.33l-35-20.23-24.32,14L195.47,197a5,5,0,0,0,7.5-4.33V5a5,5,0,0,0-7.5-4.33L32.93,94.52A5,5,0,0,0,32.93,103.18Z"/>
                            </svg>
                        </a>
                    </div>
                    <div class="flex-grow text-center text-lg text-gray-800">{{config('app.name')}}</div>
                    <div class="flex-none">
                        <button type="button"
                                class="bg-white rounded-md p-2 inline-flex items-center justify-center text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-indigo-500"
                                x-on:click="menuOpen = false"
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
                        <template x-for="(item, index) in menuItems" :key="index">
                            <li class="px-4" x-show="!item.hidden || index === menuActive">
                                <div class="flex items-center space-x-2 p-2 rounded"
                                     @click="item.url && !item.menu ? window.location.href = item.url : (menuActive === index ? menuActive = -1 : menuActive = index)"
                                     :class="{ 'bg-blue-900 text-white': menuActive === index}"
                                >
                                <span class="flex-none w-5 mr-1 fill-current  flex items-center justify-center"
                                      x-html="item.icon"></span>
                                    <span class="flex-grow" x-text="item.name"></span>
                                </div>
                                <template x-if="item.menu && item.menu.length">
                                    <div class="pt-2" x-show="index === menuActive">
                                        <template x-for="(parent, parentIndex) in item.menu" :key="parentIndex">
                                            <div>
                                                <div class="text-xs text-gray-500 py-2 px-2 pl-8"
                                                     x-text="parent.name"></div>
                                                <template x-if="parent.menu.length">
                                                    <ul>
                                                        <template x-for="(sub, subIndex) in parent.menu"
                                                                  :key="subIndex">
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
        <aside class="z-10 hidden lg:flex">
            <div class="flex-grow bg-gray-800 fixed left-0 top-0 bottom-0 text-white flex flex-col"
                 :class="{'w-24': !menuCollapse, 'w-16': menuCollapse}">
                <div class="flex-none flex flex-col items-center justify-center">
                    <div class="rounded-full h-10 w-10 flex items-center justify-center bg-white mt-5">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.97 197.7"
                             class="h-6 w-6 fill-current text-blue-900">
                            <path
                                    d="M170,94.52l-35.9-20.73-24.34,14,11.62,6.71a5,5,0,0,1,0,8.66L32.5,154.52a5,5,0,0,1-7.5-4.33V99.61a6.44,6.44,0,0,1,0-1.52V47.51a5,5,0,0,1,7.5-4.33l35,20.23,24.32-14L7.5.68A5,5,0,0,0,0,5V192.69A5,5,0,0,0,7.5,197L170,103.18A5,5,0,0,0,170,94.52Z"/>
                            <path
                                    d="M32.93,103.18l35.9,20.73,24.34-14-11.62-6.71a5,5,0,0,1,0-8.66l88.92-51.34a5,5,0,0,1,7.5,4.33V98.09a6.44,6.44,0,0,1,0,1.52v50.58a5,5,0,0,1-7.5,4.33l-35-20.23-24.32,14L195.47,197a5,5,0,0,0,7.5-4.33V5a5,5,0,0,0-7.5-4.33L32.93,94.52A5,5,0,0,0,32.93,103.18Z"/>
                        </svg>
                    </div>
                    <div class="text-xs text-gray-400 mt-2" x-show="!menuCollapse">后台管理</div>
                </div>
                <div class="flex-grow text-gray-400 p-2 mt-3 overflow-y-auto">
                    <ul>
                        <template x-for="(item, index) in menuItems" :key="index">
                            <li class="mb-2" x-show="!item.hidden || index === menuActive"
                                :class="{'mt-20': item.app === 'app'}">
                                <div
                                        x-bind="bindApp(item, index)"
                                        class="cursor-pointer block rounded-sm  py-1.5 text-center flex items-center justify-center gap-1 text-gray-300 truncate"
                                        :class="{ 'bg-gray-700 text-white': menuActive === index, 'bg-blue-900 text-white': pageActive === index, 'hover:text-white hover:bg-gray-700': menuActive !== index }"
                                >
                            <span class="w-5 fill-current  flex items-center justify-center w-5"
                                  x-html="item.icon"></span>
                                    <span x-text="item.name" x-show="!menuCollapse"></span>
                                </div>
                            </li>
                        </template>
                    </ul>

                </div>
            </div>

            <div class="flex-none">
                <template x-for="(item, index) in menuItems" :key="index">
                    <div x-show="menuActive == index">
                        <template x-if="item.menu && item.menu.length">
                            <div class="flex-none" :class="{'w-32': !menuCollapse}">
                                <div
                                        class="w-32 top-14 bg-white bottom-0 fixed z-10  px-2 pt-1 border-gray-300 border-solid border-r overflow-y-auto"
                                        :class="{'left-24': !menuCollapse, 'left-16': menuCollapse}">
                                    <template x-for="(parent, parentIndex) in item.menu" :key="parentIndex">
                                        <div>
                                            <div class="text-xs text-gray-500 py-3 px-2" x-text="parent.name"></div>
                                            <template x-if="parent.menu.length">
                                                <ul>
                                                    <template x-for="(sub, subIndex) in parent.menu" :key="subIndex">
                                                        <li>
                                                            <a :href="sub.url"
                                                               class="text-gray-800 hover:text-blue-900 block p-2 rounded truncate"
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
    </div>
    <main class="lg:flex-grow  lg:h-full lg:w-6" :class="{'lg:ml-24': !menuCollapse, 'lg:ml-16': menuCollapse}">
        <div class="h-14 hidden lg:flex">
            <header class="bg-white shadow p-2 flex gap-2 items-center z-10 fixed top-0 right-0 left-24 h-14"
                    :class="{'left-24': !menuCollapse, 'left-16': menuCollapse}">
                <div class="flex-none">
                    <svg @click="menuToggle" xmlns="http://www.w3.org/2000/svg"
                         class="h-7 w-7 p-1 text-gray-600 hover:bg-gray-300 cursor-pointer" fill="none"
                         viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </div>
                <div
                        class="flex-none flex space-x-2 pl-2 items-center text-gray-500 hover:text-gray-900 cursor-pointer select-none"
                        data-js="dialog-open" data-layout="false" data-url="{{route('admin.search')}}"
                        data-title="搜索引擎">
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
                    <ul x-show="open" @click.outside="open = false"
                        class="shadow absolute right-0 w-40 pt-1 pb-1 rounded-sm bg-white">
                        <li>
                            <a target="_blank" href="{{route('web.index')}}"
                               class="block p-2 hover:bg-gray-200">返回首页</a>
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
<script>
    Do('base', function () {
        base.init()
    });

    menuList = @json($menuList)

    document.addEventListener('alpine:init', () => {

        Alpine.data('manageInit', () => ({
            menuOpen: false,
            menuCollapse: true,
            pageActive: {{$menuActive}},
            menuActive: {{$menuActive}},
            menuItems: menuList,
            init() {
                this.menuCollapse = localStorage.getItem('menuCollapse') ? true : false
                this.menuActive = this.menuCollapse ? -1 : this.menuActive;
            },
            menuToggle() {
                this.menuActive = this.menuCollapse ? this.pageActive : -1
                this.menuCollapse = !this.menuCollapse
                if (this.menuCollapse) {
                    localStorage.setItem('menuCollapse', true)
                } else {
                    localStorage.removeItem('menuCollapse')
                }
            },
            bindAside: {
                ['@mouseleave']() {
                    this.menuActive = this.menuCollapse ? -1 : this.pageActive
                },
            },
            bindApp: (item, index) => {
                return {
                    ['@mouseover']() {
                        this.menuActive = index
                    },
                    ['@click']() {
                        window.location.href = item.url
                    }
                }
            }
        }))
    })

</script>
</html>
