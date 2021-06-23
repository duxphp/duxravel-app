@if($type == 2)
    <svg xmlns="http://www.w3.org/2000/svg" class="icon ml-1" width="24" height="24"
         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
         stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                                                              fill="none"/><polyline
            points="3 17 9 11 13 15 21 7"/><polyline points="14 7 21 7 21 14"/></svg>
@elseif($type == 1)
    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24"
         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
         stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                                                              fill="none"></path><line x1="5"
                                                                                       y1="12"
                                                                                       x2="19"
                                                                                       y2="12"></line></svg>
@else
    <svg xmlns="http://www.w3.org/2000/svg" class="icon ms-1" width="24" height="24"
         viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
         stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z"
                                                              fill="none"></path><polyline
            points="3 7 9 13 13 9 21 17"></polyline><polyline
            points="21 10 21 17 14 17"></polyline></svg>
@endif
