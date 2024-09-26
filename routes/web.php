<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//REGISTRARSE E INICIAR SESIÓN
Route::get('/auth', [AuthController::class, 'showAuthForm'])->name('auth');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
//Redigie /login, /register, /logout a auth
Route::get('/login', function() {
    return redirect()->route('auth');
});
Route::get('/register', function() {
    return redirect()->route('auth');
});
Route::get('/logout', function() {
    return redirect()->route('auth');
});

//RUTAS QUE REQUIERE AUTENTICACIÓN
Route::middleware('auth')->group(function(){
    //PANTALLA DE INICIO (CHAT)
    Route::get('/index',[ChatController::class, 'showChatForm'])->name('index');
    // Ruta para enviar mensajes
    Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send.message');
});
