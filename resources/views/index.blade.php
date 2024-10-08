@include('header')
<body>
<div class="container-index">
@include('sidebar')
<div class="content-index">
    <div class="index-chat">
    <!-- Si hay un chat seleccionado, mostrar los mensajes -->
    @if($selectedChat)
    <!-- $selectedChat->user_one_id: Es el ID del primer participante del chat (primer usuario que inició el chat).
    $selectedChat->userTwo->name: Si el usuario autenticado es el primer participante (user_one_id), se muestra el nombre del segundo participante del chat (userTwo).
    $selectedChat->userOne->name: Si el usuario autenticado no es el primer participante (es el segundo, user_two_id), entonces se muestra el nombre del primer participante del chat (userOne). -->
    <div class="header-chat">
    <h3>{{ $selectedChat->user_one_id == auth()->id() ? $selectedChat->userTwo->name : $selectedChat->userOne->name }}</h3>
    <div class="menu-icon" id="menuIcon">⋮</div>
    <ul class="menu-options" id="menuOptions">
        <li>
    <!-- Botón para vaciar el chat -->
    <form action="{{ route('chat.empty', $selectedChat->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas vaciar este chat?');">
            @csrf
            @method('DELETE')
            <button type="submit">Vaciar Chat</button>
        </form>
        </li>
        </ul>
        </div> <!--header-chat-->
    <!-- Mostrar mensajes -->
    <div class="chat-box">
    @foreach($messages as $message)
    <div class="chat-bubble {{ $message->sender->id == auth()->id() ? 'sent' : 'received' }}">
        <strong>{{ $message->sender->name }}</strong>
        <small>{{ $message->created_at->diffForHumans() }}</small>
        <p>{{ $message->message }}</p>
    </div>
    @endforeach
</div>

    
    <!-- Formulario para enviar mensaje -->
    <form class="chat-form" id="chatForm" action="{{ route('send.message') }}" method="POST">
        @csrf
        <!-- Se está utilizando para enviar el ID del otro usuario en el chat.
        $selectedChat->user_one_id == auth()->id():
        Este fragmento compara el ID del primer usuario en el chat (user_one_id) con el ID del usuario autenticado (auth()->id()).
        Si son iguales, significa que el usuario autenticado es el primer participante del chat.
        Operador ternario (? :):
        Si la condición es verdadera (el usuario autenticado es user_one): Se asigna el ID del segundo usuario del chat ($selectedChat->user_two_id) como el valor del campo user_id.
        Si la condición es falsa (el usuario autenticado es user_two): Se asigna el ID del primer usuario del chat ($selectedChat->user_one_id). -->
        <input type="hidden" name="user_id" value="{{ $selectedChat->user_one_id == auth()->id() ? $selectedChat->user_two_id : $selectedChat->user_one_id }}">
        <textarea name="message" id="messageInput"  placeholder="Escribe tu mensaje" required></textarea>
        <button type="submit"><i class="material-icons">&#xe163;</i>
        </button>
    </form>
    </div> <!--index-chat-->
@else
    <p class="select-chat">Selecciona un usuario o chat para comenzar a chatear.</p>
@endif
</div><!--content-index-->
</div> <!--container -->
<script>
     $(document).ready(function() {
        // Manejar envío de mensaje con AJAX
        $('#chatForm').on('submit', function(event) {
            event.preventDefault(); // Evitar que el formulario recargue la página

            var form = $(this); //Obtiene el formulario en el que ocurrió el evento (es decir, #chatForm).
            var message = $('#messageInput').val(); //Obtiene el valor ingresado en el campo de texto del mensaje (#messageInput).
            
            // Enviar el formulario mediante AJAX
            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),
                success: function(response) {
                    $('#messageInput').val(''); // Limpiar el campo de mensaje
                    loadMessages(); // Cargar mensajes actualizados
                },
                error: function(xhr) {
                    alert('Error al enviar el mensaje.');
                }
            });
        });

        // Función para cargar mensajes sin recargar la página
        function loadMessages() {
            // Obtiene el ID del chat seleccionado si existe ($selectedChat). Si no hay ningún chat seleccionado, el valor es null.
            var chatId = '{{ $selectedChat ? $selectedChat->id : null }}'; // Verifica si $selectedChat no es null
            if (chatId) {
                $.ajax({
                    url: '{{ route('get.messages') }}', // Ruta específica para cargar solo los mensajes
                    type: 'GET',
                    data: { chat_id: chatId }, //Pasa el ID del chat seleccionado como parámetro en la solicitud.
                    success: function(data) {
                        $('.chat-box').html(data); // Actualizar la lista de mensajes
                    },
                    error: function(xhr) {
                        alert('Error al cargar mensajes.');
                    }
                });
            }
        }

        // Intervalo para cargar mensajes automáticamente cada 5 segundos
        setInterval(loadMessages, 3000);
    });

    //Responsive
    const nav = document.querySelector("#navegation");
        const abrir = document.querySelector("#open");
        const cerrar = document.querySelector("#close");
        const cerrarLinks = document.querySelectorAll(".close-link");

        abrir.addEventListener("click", () => {
            nav.classList.add("visible");
            console.log("Navegación abierta"); // Agrega un mensaje para depuración
        });

        cerrar.addEventListener("click", () => {
            nav.classList.remove("visible");
        });

        cerrarLinks.forEach(link => {
            link.addEventListener("click", () => {
                nav.classList.remove("visible");
            });
        });



            // Obtener los elementos
const menuIcon = document.getElementById('menuIcon');
const menuOptions = document.getElementById('menuOptions');

// Agregar un event listener al icono de menú
menuIcon.addEventListener('click', function() {
  // Alternar entre mostrar y ocultar el menú
  if (menuOptions.style.display === 'block') {
    menuOptions.style.display = 'none';
  } else {
    menuOptions.style.display = 'block';
  }
});

// Ocultar el menú si se hace clic fuera del menú
window.addEventListener('click', function(event) {
  if (!menuIcon.contains(event.target)) {
    menuOptions.style.display = 'none';
  }
});
</script>

</body>
</html>