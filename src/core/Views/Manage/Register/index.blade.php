<div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                 x="0px"
                 y="0px"
                 viewBox="0 0 12 16" xml:space="preserve" class="mx-auto h-16 w-16 fill-current text-blue-900">
                <g>
                    <polygon
                        points="6.66,12.04 6.66,8.92 3.43,8.92 3.43,3.96 5.34,3.96 5.34,7.08 8.57,7.08 8.57,12.04 	"/>
                    <polygon
                        points="3.43,15.17 3.43,10.21 5.34,10.21 5.34,13.33 9.9,13.33 9.9,0.83 11.81,0.83 11.81,15.17 	"/>
                    <polygon
                        points="0.19,15.17 0.19,0.83 8.57,0.83 8.57,5.79 6.66,5.79 6.66,2.67 2.1,2.67 2.1,15.17 	"/>
                </g>
            </svg>


            <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                {{config('app.name')}}
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                <span class="font-medium text-blue-600 hover:text-blue-900">
                    首次使用请先注册管理账号
                </span>
            </p>
        </div>
        <form class="mt-8 space-y-6" action="#" action="{{route('admin.register.submit')}}" method="post"
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
                <div>
                    <input name="password_confirmation" type="password"  required
                           class="appearance-none rounded-none relative block w-full p-4 border border-gray-300 placeholder-gray-500 text-gray-900 rounded-b-md focus:outline-none focus:ring-2 focus:ring-blue-900 focus:border-blue-900 focus:z-10 text-sm"
                           placeholder="重复密码">
                </div>
            </div>

            <div>
                <button type="submit" class="btn-blue w-full">
                    注册
                </button>
            </div>
        </form>
    </div>
</div>
