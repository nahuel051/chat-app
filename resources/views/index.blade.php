<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
</head>
<body>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Cerrar Sesión</button>
</form>
<form action="{{ route('delete.user') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')">
    @csrf
    @method('DELETE')
    
    <button type="submit">Eliminar cuenta</button>
</form>
<b>{{ auth()->user()->name }}</b>
    <!-- Lista de usuarios -->
    <h3>Usuarios disponibles para chatear</h3>
    <ul>
        @foreach($users as $user)
        <!-- ['user_id' => $user->id]: Este arreglo especifica los parámetros que se pasarán en la URL. En este caso, se está enviando el parámetro user_id, que tiene el valor de $user->id, es decir, el ID del usuario iterado en el bucle http://tu-aplicacion.com/index?user_id=3
        -->
            <li>
            <a href="{{ route('index', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
            </li>
        @endforeach
    </ul>
    <!-- Lista de chats existentes -->
    <h3>Chats</h3>
    <ul>
        @foreach($chats as $chat)
            <li>
                <a href="{{ route('index', ['chat_id' => $chat->id]) }}">
                <!-- $chat->user_one_id == auth()->id(): Este condicional verifica si el usuario autenticado (auth()->id()) es el primer participante del chat (user_one_id). -->
                    @if($chat->user_one_id == auth()->id())
                    <!-- Si el usuario autenticado es user_one_id: Muestra el nombre del otro participante del chat, es decir, userTwo. Aquí se utiliza la relación definida en el modelo Chat con el método userTwo(): -->
                    {{ $chat->userTwo->name }}
                    @else
                    <!-- Si el usuario autenticado no es user_one_id: Significa que es el segundo participante del chat, por lo que muestra el nombre del primer usuario (userOne): -->
                        {{ $chat->userOne->name }}
                    @endif
                    {{-- Mostrar la cantidad de mensajes no leídos --}}
                @php
                //Cuenta los mensajes no leidos, filtrados anteriormente con el uso de with()
                    $unreadCount = $chat->messages->count();
                @endphp
                <!-- Verifica si mensajes no leidos son mayor a eso, si hay mostrar el mensaje -->
                @if($unreadCount > 0)
                    <span>({{ $unreadCount }} no leídos)</span>
                @endif
                </a>
            </li>
        @endforeach
    </ul>
    <!-- Si hay un chat seleccionado, mostrar los mensajes -->
    @if($selectedChat)
    <!-- $selectedChat->user_one_id: Es el ID del primer participante del chat (primer usuario que inició el chat).
    $selectedChat->userTwo->name: Si el usuario autenticado es el primer participante (user_one_id), se muestra el nombre del segundo participante del chat (userTwo).
    $selectedChat->userOne->name: Si el usuario autenticado no es el primer participante (es el segundo, user_two_id), entonces se muestra el nombre del primer participante del chat (userOne). -->
    <h3>Chat con {{ $selectedChat->user_one_id == auth()->id() ? $selectedChat->userTwo->name : $selectedChat->userOne->name }}</h3>
    <!-- Botón para vaciar el chat -->
    <form action="{{ route('chat.empty', $selectedChat->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas vaciar este chat?');">
            @csrf
            @method('DELETE')
            <button type="submit">Vaciar Chat</button>
        </form>

    <!-- Mostrar mensajes -->
    <div class="chat-box">
        @foreach($messages as $message)
            <p><strong>{{ $message->sender->name }}:</strong> {{ $message->message }}</p>
        @endforeach
    </div>
    
    <!-- Formulario para enviar mensaje -->
    <form action="{{ route('send.message') }}" method="POST">
        @csrf
        <!-- Se está utilizando para enviar el ID del otro usuario en el chat.
        $selectedChat->user_one_id == auth()->id():
        Este fragmento compara el ID del primer usuario en el chat (user_one_id) con el ID del usuario autenticado (auth()->id()).
        Si son iguales, significa que el usuario autenticado es el primer participante del chat.
        Operador ternario (? :):
        Si la condición es verdadera (el usuario autenticado es user_one): Se asigna el ID del segundo usuario del chat ($selectedChat->user_two_id) como el valor del campo user_id.
        Si la condición es falsa (el usuario autenticado es user_two): Se asigna el ID del primer usuario del chat ($selectedChat->user_one_id). -->
        <input type="hidden" name="user_id" value="{{ $selectedChat->user_one_id == auth()->id() ? $selectedChat->user_two_id : $selectedChat->user_one_id }}">
        <textarea name="message" placeholder="Escribe tu mensaje" required></textarea>
        <button type="submit">Enviar</button>
    </form>
@else
    <p>Selecciona un usuario o chat para comenzar a chatear.</p>
@endif


</body>
</html>