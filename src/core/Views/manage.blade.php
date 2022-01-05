<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="UTF-8" />
  <link rel="icon" href="/favicon.ico" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0">
  <title>{{config('app.name')}}</title>
  <script type="module" src="/{{ $mainJs }}"></script>
  <link rel="stylesheet" href="/{{ $mainCss }}" />
  <script>
    window.appConfig = {
      name: '{{$info['name'] ?: config('app.name')}}',
      logo: '/images/logo.svg',
      login: {
        logo: '/images/logo.svg',
        title: '{{$info['login']['title'] ?:config('app.name')}}',
        name: '{{$info['login']['name'] ?: '系统登录'}}',
        desc: '{{$info['login']['desc'] ?:config('app.desc')}}',
        contact: '{{$info['login']['contact'] ?: config('app.contact')}}',
        background: '{{is_file(public_path('/images/login-bg.png')) ? "/images/login-bg.png" : ""}}',
        side: {!! json_encode($sideImages ?: []) !!}
      }
    }
    // 屏蔽warn
    console.warn = () => { }
  </script>
</head>
<body>
<div class="bg-gray-100 dark:bg-blackgray-1"  id="duxravel-static"></div>
</body>
</html>
