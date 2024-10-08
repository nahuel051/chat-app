@include('header')
<div class="container-index">
@include('sidebar')

<body>
    <div class="search-container">
        <!-- Lista de usuarios -->
        <h3>Usuarios disponibles para chatear</h3>
        <!-- Input de búsqueda -->
    <input type="text" id="searchInput" placeholder="Buscar usuarios...">
    <ul id="usersList">
        @foreach($users as $user)
        <!-- ['user_id' => $user->id]: Este arreglo especifica los parámetros que se pasarán en la URL. En este caso, se está enviando el parámetro user_id, que tiene el valor de $user->id, es decir, el ID del usuario iterado en el bucle http://tu-aplicacion.com/index?user_id=3
        -->
            <li>
            <a href="{{ route('index', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
            </li>
        @endforeach
    </ul>
    </div>
    </div>
</body>
<script>
    $(document).ready(function() {
        // Función de búsqueda dinámica
        $('#searchInput').on('keyup', function() {
            var query = $(this).val(); // Captura el valor del input

            // Enviar solicitud AJAX para filtrar usuarios
            $.ajax({
                url: '{{ route("search.users") }}',  // Cambiar a la ruta correcta
                type: 'GET',
                data: { query: query },
                success: function(data) {
                    // Actualizar la lista de usuarios con los resultados
                    $('#usersList').html(data);
                },
                error: function(xhr) {
                    alert('Error al buscar usuarios.');
                }
            });
        });
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

</script>

</html>