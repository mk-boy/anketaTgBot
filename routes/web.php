<?php

use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\FileUpload\InputFile;
use App\Library\TelegramKeyboardHelper;

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

Route::get('/', function () {
    return redirect('/admin');
});

Route::get('/test', function () {
    Telegram::sendPhoto([
        'chat_id' => "905189967",
        'photo' => new InputFile('https://i.ibb.co/Z6g7Ghjx/YANDEX-15-SEP-2023-1373.jpgs'),
        'caption' => trans('admin.final_message') . trans('admin.yandex_href'),
        'parse_mode' => 'HTML'
    ]);
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/webhook', App\Http\Controllers\WebhookController::class);

Route::get('/telegram', [App\Http\Controllers\WebhookController::class, 'setWebhook']);

Route::get('/admin/executors', [App\Http\Controllers\AdminViewsController::class, 'executors'])->middleware('auth');

Route::get('/export-anketa', [App\Http\Controllers\ExportController::class, 'exportViaConstructorInjection']);
