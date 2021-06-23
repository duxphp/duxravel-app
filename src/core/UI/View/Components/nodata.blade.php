<div {{ $attributes }}>
    <div class="flex flex-col items-center">
        <div class="w-20 h-20">
            <svg class="w-full h-full" viewBox="0 0 140 140" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                <g id="06.-Design-Variations" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <g id="02-example-products-empty" transform="translate(-776.000000, -284.000000)">
                        <g id="empty-state" transform="translate(290.000000, 164.000000)">
                            <g id="illustration" transform="translate(486.000000, 120.000000)">
                                <rect id="illustration-bg" fill="#FFFFFF" opacity="0" x="0" y="0" width="140" height="140"></rect>
                                <g id="receipt" transform="translate(28.000000, 16.000000)">
                                    <path d="M78,0.979732163 L6,1 L6,61.6706477 C6,62.220134 78,61.9838101 78,61.6706477 L78,0.979732163 Z" id="Path" fill="#D7DBEC"></path>
                                    <polygon id="Path" fill="#336DFF" points="84 9 0 9 0 109 5.5 103 10.5 109 16 103 21 109 26.5 103 31.5 109 37 103 42 109 47.5 103 52.5 109 58 103 63 109 68.5 103 73.5 109 79 103 84 109"></polygon>
                                    <polygon id="Path" fill="#FFFFFF" fill-rule="nonzero" points="21.6465315 28.5275452 24.3534685 31.4724548 15.3588167 39.740266 10.6190485 35.2159419 13.3809515 32.3225197 15.41 34.26"></polygon>
                                    <rect id="Rectangle" fill="#FFFFFF" x="32" y="32" width="38" height="4"></rect>
                                    <polygon id="Path" fill="#FFFFFF" fill-rule="nonzero" points="21.6465315 45.5275452 24.3534685 48.4724548 15.3588167 56.740266 10.6190485 52.2159419 13.3809515 49.3225197 15.41 51.26"></polygon>
                                    <rect id="Rectangle" fill="#FFFFFF" x="32" y="49" width="38" height="4"></rect>
                                    <polygon id="Path" fill="#FFFFFF" fill-rule="nonzero" points="21.6465315 62.5275452 24.3534685 65.4724548 15.3588167 73.740266 10.6190485 69.2159419 13.3809515 66.3225197 15.41 68.26"></polygon>
                                    <rect id="Rectangle" fill="#FFFFFF" x="32" y="66" width="38" height="4"></rect>
                                </g>
                            </g>
                        </g>
                    </g>
                </g>
            </svg>
        </div>
        <p class="text-lg mb-2 mt-4">{{$title}}</p>
        <p class="text-gray-500">
            {{$content}}
        </p>
        @if($reload)
        <div class="mt-5">
            <a href="javascript:location.reload();" class="btn-blue flex items-center space-x-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M19.95 11a8 8 0 1 0 -.5 4m.5 5v-5h-5" /></svg>
                <span>刷新页面</span>
            </a>
        </div>
        @endif
    </div>
</div>
