<?php

use Illuminate\Support\Facades\Route;

/**
 * 基础路由
 */
Route::get('/', [\Duxravel\Core\Web\Index::class, 'index'])->middleware('web')->name('web.index');
Route::get('service/image/placeholder/{w}/{h}/{t}', [\Duxravel\Core\Web\Image::class, 'placeholder'])->middleware('web')->name('service.image.placeholder');
Route::get('service/area', [\Duxravel\Core\Web\Area::class, 'index'])->middleware('web')->name('service.area');