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

// RUTAS QUE REQUIEREN AUTENTICACIÓN
Route::middleware('auth')->group(function(){
    // Pantalla de inicio (chat)
    Route::get('/index', [ChatController::class, 'showChatForm'])->name('index');
// Cargar la vista completa de búsqueda
Route::get('/search', [ChatController::class, 'showSearchForm'])->name('search');
    
// Búsqueda dinámica con AJAX
Route::get('/search-users', [ChatController::class, 'searchUsers'])->name('search.users');
    // Ruta para enviar mensajes
    Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('send.message');
    // Llamando al método getMessages del ChatController. la ruta tiene un nombre ('get.messages')
    Route::get('/get-messages', [ChatController::class, 'getMessages'])->name('get.messages');
    //Ruta para eliminar chat
    Route::delete('/chat/{chat_id}/empty', [ChatController::class, 'emptyChat'])->name('chat.empty');
    Route::delete('delete-user', [ChatController::class, 'deleteUser'])->name('delete.user');
    
});
