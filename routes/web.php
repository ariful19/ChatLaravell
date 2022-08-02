<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FineTestController;
use App\Http\Controllers\ChatController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/fine-test', [FineTestController::class, 'index']);
Route::post('/fine-test/fileUpload', [FineTestController::class, 'fileUpload']);
Route::get('/fine-test/dbTest', [FineTestController::class, 'dbTest']);
Route::get('/chat', [ChatController::class, 'index']);
Route::get('/Chat/notify', [ChatController::class, 'notify']);
Route::post('/Chat/UpdateUser', [ChatController::class, 'UpdateUser']);
Route::get('/Chat/GetUsers', [ChatController::class, 'GetUsers']);
Route::get('/Chat/GetChats', [ChatController::class, 'GetChats']);
Route::post('/Chat/FileUpload', [ChatController::class, 'FileUpload']);
