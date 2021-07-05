<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <title>{{config('app.name')}}</title>
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
    <script src="/static/system/js/app.min.js"></script>
</head>
<body class="bg-gray-100 text-sm">
    @include($layout)
</body>
<script src="https://lib.baomitu.com/alpinejs/3.2.1/cdn.min.js"></script>
<script>
    Do('base', function () {
        base.init()
    });
</script>
</html>
