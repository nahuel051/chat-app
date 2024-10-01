<div class="container-nav">
<button id="open" class="open">
    <i class="fa-solid fa-bars"></i>
</button>
<div class="navegation" id="navegation">
<button id="close" class="close">
        <i class="fa-solid fa-xmark"></i>
</button>
<div class="content-nav">
<b><i class="fa-solid fa-message"></i>{{ auth()->user()->name }}</b>
<ul class="nav-ul">
<li class="close-link"><a href="{{route('index')}}"><i class="fa-solid fa-house"></i>Inicio</a></li>
<li class="close-link"><a href="{{route('search')}}"><i class="fa-solid fa-magnifying-glass"></i>Buscar Usuarios</a></li>
<li class="close-link"><form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit"><i class="fa-solid fa-right-from-bracket"></i>Cerrar Sesión</button>
</form></li>
<li class="close-link"><form action="{{ route('delete.user') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')">
    @csrf
    @method('DELETE')
    
    <button type="submit"><i class="fa-solid fa-delete-left"></i>Eliminar cuenta</button>
</form></li>
</ul> <!-- nav-ul -->
    <!-- Lista de chats existentes -->
    <h3>Chats</h3>
    <ul>
        @foreach($chats as $chat)
            <li class="close-link">
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
    </div> <!-- content-nav -->
    </div> <!-- navegation -->
    </div> <!-- conainter-nav -->