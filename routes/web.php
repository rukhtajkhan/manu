<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ManuController;


Route::get('/',[AuthController::class, 'index'])->name('show.index');
Route::post('/creeat/user',[AuthController::class, 'store'])->name('create.user');
Route::get('/login/page',[AuthController::class, 'login'])->name('show.login');
Route::post('/login/user', [AuthController::class, 'attamped'])->name('login');
Route::post('/add-menu', [ManuController::class, 'addMenu']);
Route::post('/update-menu-parent', [ManuController::class, 'updateParent'])->name('update-menu-parent');
Route::get('/get-menu-list', [ManuController::class, 'getMenuList'])->name('get-menu-list');
// Route to reset parent_id to null
Route::post('/reset-menu-parent', [ManuController::class, 'resetParentToNull'])->name('reset-menu-parent');







