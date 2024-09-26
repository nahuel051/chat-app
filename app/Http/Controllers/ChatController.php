<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\Message;
use Auth;
use Illuminate\Http\Request;
use App\Models\User;
class ChatController extends Controller
{
//OBTENER CHATS
public function showChatForm(Request $request){
    // Obtener usuarios excepto el autenticado
    $users = User::where('id', '!=', auth()->id())->get();

    // Obtener los chats donde participa el usuario autenticado
    $chats = Chat::where('user_one_id', auth()->id())
                 ->orWhere('user_two_id', auth()->id())
                 //with se utiliza para hacer carga ansiosa (eager loading) de las relaciones. Se esta cargando la realcion messages del modelo Chat que corresponde a los mensajes asociados
                 //$query->where('is_read', false): una condición para que solo traiga los mensajes cuyo campo is_read sea false, es decir, los mensajes no leídos.
                 //where('user_id', '!=', auth()->id()):  asegura que solo se obtengan los mensajes enviados por alguien que no sea el usuario autenticado)
                 ->with(['messages' => function($query) {
                    // Trae solo los mensajes no leídos del usuario autenticado
                    $query->where('is_read', false)->where('user_id', '!=', auth()->id());
                }])
                 ->get();

//No hay chat seleccionado. Almacenar el objeto del chat que el usuario selecciona. Si el usuario no selecciona un chat se mantiene como null.
$selectedChat = null;
//Inicializa como arreglo vacio. Prepara una lista donde se almacenaran los mensajes del chat seleccionado. Si hay un chat seleccionado esta variable se llenara con los mensajes correspondientes a ese chat, sino estara vacio.
$messages = [];

    // Si el request tiene 'chat_id', mostrar los mensajes de ese chat
    // has('chat_id') comprueba si existe el parámetro 'chat_id' en la solicitud HTTP (request).
    if ($request->has('chat_id')) {
        $selectedChat = Chat::find($request->chat_id);
        if ($selectedChat) {
            $messages = $selectedChat->messages()->orderBy('created_at', 'asc')->get();
            // Marcar mensajes como leídos
            // este método actualiza el campo is_read a true para todos los mensajes que cumplan con las condiciones anteriores, es decir, marca esos mensajes como "leídos".
        $selectedChat->messages()->where('user_id', '!=', auth()->id())->update(['is_read' => true]);
        }
    }
    // Si el request tiene 'user_id', seleccionar el usuario con quien iniciar un chat
    // 'user_id' proviene de la URL cuando el usuario hace clic en uno de los usuarios disponibles para iniciar un nuevo chat en la vista
    elseif ($request->has('user_id')) {
        // Verificar si ya existe un chat entre los usuarios
        $selectedChat = Chat::where(function($query) use ($request) {
            $query->where('user_one_id', auth()->id())
                  ->where('user_two_id', $request->user_id);
        })->orWhere(function($query) use ($request) {
            $query->where('user_one_id', $request->user_id)
                  ->where('user_two_id', auth()->id());
        })->first();

        // Si no existe el chat, crear uno nuevo
        if (!$selectedChat) {
            $selectedChat = Chat::create([
                'user_one_id' => auth()->id(),
                'user_two_id' => $request->user_id
            ]);
        }
    }

    return view('index', compact('users', 'chats', 'selectedChat', 'messages'));
}
// El valor de $request->user_id proviene de los datos enviados en la solicitud (request) cuando:
// El usuario selecion a otro usuario de la lista:
// <ul>
//     @foreach($users as $user)
//         <li>
//             <a href="{{ route('index', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
//         </li>
//     @endforeach
// </ul>
// Cuando haces clic en uno de esos enlaces, el parámetro user_id se envía en la URL. Por ejemplo: http://tu-aplicacion.com/index?user_id=2
// Cuando se envia un mensaje: En el formulario de envío de mensajes, el user_id también se envía como un campo oculto (hidden) en el formulario:     <input type="hidden" name="user_id" value="{{ $selectedChat->user_one_id == auth()->id() ? $selectedChat->user_two_id : $selectedChat->user_one_id }}">
// En este caso, el user_id representa al otro usuario con el que se está chateando, y se envía en el formulario cuando se envía un mensaje.

    //ENVIAR MENSAJES
    public function sendMessage(Request $request){
        $request->validate([
            'message' => 'required|string',
            'user_id'=> 'required|exists:users,id'
        ]);
        // Verificar si ya existe un chat entre el usuario autenticado y el usuario seleccionado
        //function($query) permite agregar condicones a la consulta
        $chat = Chat::where(function($query) use ($request) {
            //PRIMERA CONDICION:
            //-user_one_id sea igual del usuario autenticado y user_two_id sea igual del id seleccionar que se paso a traves de $request->user_id
            $query->where('user_one_id', auth()->id())
                  ->where('user_two_id', $request->user_id);
        })->orWhere(function($query) use ($request) {
            //SEGUNDA CONDICION:
            //user_one_id sea id del usuario seleccionado y user_two_id sea igual del usuario autenticado
            $query->where('user_one_id', $request->user_id)
                  ->where('user_two_id', auth()->id());
        })->first(); //ejecuta y devuelve el primer resultado si no hay devuelve null

        //Si no existe el chat, crear uno nuevo
        if(!$chat){
            $chat = Chat::create([
                'user_one_id'=>auth()->id(),
                'user_two_id'=>$request->user_id
            ]);
        }
        //Crear el mensaje
        Message::create([
            'chat_id'=>$chat->id,
            'user_id'=>auth()->id(),
            'message'=>$request->message,
        ]);
        // El segundo parámetro del método route() es un arreglo que contiene los parámetros que deben incluirse en la URL. En este caso, el parámetro chat_id se establece como el ID del chat recién creado o encontrado ($chat->id).
        // Por ejemplo, si el ID del chat es 5, la URL generada será algo como:
        return redirect()->route('index',['chat_id'=>$chat->id]);
    }
    
    public function emptyChat($chat_id){
        $chat = Chat::findOrFail($chat_id);
        //Eliminar todos los chat asociados con ese chat
        $chat->messages()->delete();
        //Redirigir devuelta a la vista de los chats
        return redirect()->route('index')->with('success','Chat vaciado con exito!');
    }

    public function deleteUser(){
        $users = Auth::user();
        $users->delete();
        Auth::logout();
        return redirect()->route('auth');
    }
}
