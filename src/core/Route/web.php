<?php

use Illuminate\Support\Facades\Route;

/**
 * 基础路由
 */
Route::get('/', [\Duxravel\Core\Web\Index::class, 'index'])->middleware('web')->name('web.index');