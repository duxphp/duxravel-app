<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{$msg ?: 'success'}} - {{config('app.name')}}</title>
    <meta name="msapplication-TileColor" content="#206bc4"/>
    <meta name="theme-color" content="#206bc4"/>
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
    <meta name="apple-mobile-web-app-capable" content="yes"/>
    <meta name="mobile-web-app-capable" content="yes"/>
    <meta name="HandheldFriendly" content="True"/>
    <meta name="MobileOptimized" content="320"/>
    <meta name="robots" content="noindex,nofollow,noarchive"/>
    <meta name="csrf-token" content="{{ csrf_token() }}">
  <link crossorigin="anonymous" href="https://lib.baomitu.com/tailwindcss/2.2.15/tailwind.min.css" rel="stylesheet">
</head>
<body class="border-t-2 border-blue-600 flex flex-col bg-gray-100 h-screen">
<div class="flex items-center justify-center flex-auto">
    <div class="max-w-2xl py-6">
        <div class="flex items-center justify-center flex-col">
            <p class="mb-2 mt-4 text-lg">{{$msg ?: 'success'}}</p>
            <p class="text-gray-500">
                操作成功，页面将在<span id="time">4</span>秒后自动跳转
            </p>
            <div class="mt-10 flex gap-4 justify-center">
                <a href="javascript:{{$url ? 'window.location.href=\''.$url.'\'' : 'window.location.href=document.referrer'}};"
                   class="btn-blue flex items-center space-x-3 text-sm">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                         stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    <div>立即跳转</div>
                </a>
                <a href="{{route('web.index')}}"
                   class="btn flex items-center space-x-3 text-sm">
                    <div>回到首页</div>
                </a>
            </div>
        </div>
    </div>
</div>
<script language="javascript">
    let num = 4;
    let url = "{{$url}}";
    window.setTimeout("autoJump()", 1000);

    function autoJump() {
        if (num !== 0) {
            document.querySelector('#time').innerHTML = num;
            num--;
            window.setTimeout("autoJump()", 1000);
        } else {
            num = 4;
            if (url) {
                window.location.href = url;
            } else {
                window.location.href = document.referrer;
            }
        }
    }
</script>
</body>
</html>
