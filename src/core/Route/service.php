<?php

use Illuminate\Support\Facades\Route;

/**
 * 基础路由
 */
Route::get('image/placeholder/{w}/{h}/{t}', [\Duxravel\Core\Web\Image::class, 'placeholder'])->middleware('web')->name('service.image.placeholder');
Route::get('area', [\Duxravel\Core\Web\Area::class, 'index'])->middleware('web')->name('service.area');