<?php

use Illuminate\Support\Facades\Route;


Route::get('app-form/{id}', ['uses' => 'Duxravel\Core\Api\Form@list', 'desc' => '表单列表'])->name('api.core.form.list');
Route::get('app-form-info/{id}', ['uses' => 'Duxravel\Core\Api\Form@info', 'desc' => '表单列表'])->name('api.core.form.list');
Route::post('app-form-info/{id}', ['uses' => 'Duxravel\Core\Api\Form@push', 'desc' => '表单提交'])->name('api.core.form.push');

