<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 202.97 197.7" class="mx-auto h-16 w-16 fill-current text-blue-900">
                <path d="M170,94.52l-35.9-20.73-24.34,14,11.62,6.71a5,5,0,0,1,0,8.66L32.5,154.52a5,5,0,0,1-7.5-4.33V99.61a6.44,6.44,0,0,1,0-1.52V47.51a5,5,0,0,1,7.5-4.33l35,20.23,24.32-14L7.5.68A5,5,0,0,0,0,5V192.69A5,5,0,0,0,7.5,197L170,103.18A5,5,0,0,0,170,94.52Z"/>
                <path d="M32.93,103.18l35.9,20.73,24.34-14-11.62-6.71a5,5,0,0,1,0-8.66l88.92-51.34a5,5,0,0,1,7.5,4.33V98.09a6.44,6.44,0,0,1,0,1.52v50.58a5,5,0,0,1-7.5,4.33l-35-20.23-24.32,14L195.47,197a5,5,0,0,0,7.5-4.33V5a5,5,0,0,0-7.5-4.33L32.93,94.52A5,5,0,0,0,32.93,103.18Z"/>
            </svg>


            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                {{config('app.name')}}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                <span class="font-medium text-blue-600 hover:text-blue-900">
                    欢迎使用后台管理系统
                </span>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="#" action="{{route('admin.login.submit')}}" method="post"
              data-js="form-bind">
            <input type="hidden" name="remember" value="true">
            <div class="rounded-md shadow-sm -space-y-px">
                <div>
                    <input name="username" type="text" autocomplete="username" required
                           class=" appearance-none rounded-none relative block w-full p-4 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-t-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 focus:z-10 text-sm"
                           placeholder="用户名">
                </div>
                <div>
                    <input name="password" type="password" autocomplete="password" required
                           class="appearance-none rounded-none relative block w-full p-4 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 focus:z-10 text-sm"
                           placeholder="登录密码">
                </div>
            </div>

            <div class="flex items-center justify-between">
                <div class="flex items-center">
                    <input id="remember_me" name="remember" type="checkbox"
                           class="form-checkbox">
                    <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                        记住密码
                    </label>
                </div>
            </div>

            <div>
                <button type="submit" class="btn-blue w-full">
                    登录
                </button>
            </div>
        </form>
    </div>
</div>
