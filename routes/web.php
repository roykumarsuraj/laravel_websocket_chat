<?php
use App\Http\Controllers\ViewController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/logout', [ViewController::class, 'logout'])->name('logout');
Route::get('/', [ViewController::class, 'index'])->name('index');
Route::get('/home', [ViewController::class, 'home'])->name('home');

Route::post('/dologin', [PostController::class, 'dologin'])->name('dologin');

Route::post('/chat/send', [ChatController::class, 'send'])->name('chat.send');
