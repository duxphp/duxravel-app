<?php
// 占位图
Route::get('image/placeholder/{w}/{h}/{t}', [Duxravel\Core\Web\Image::class, 'placeholder'])->name('service.image.placeholder');

// 省市区街道数据
Route::get('area', [Duxravel\Core\Web\Area::class, 'index'])->name('service.area');
