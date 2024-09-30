<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <form id="registerForm" action="{{route('register')}}" method="post">
        @csrf
        <input type="text" name="name" placeholder="Nombre">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="password" name="password_confirmation" placeholder="Repetir contraseña">
        <button type="submit">Registrarse</button>
        <div id="registerErrorMessages"></div>
    </form>
    <hr>
    <form id="loginForm" action="{{route('login')}}" method="post">
        @csrf
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit">Iniciar Sesión</button>
        <div id="loginErrorMessages"></div>
    </form>
    <!-- @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif -->
    <script>
         $(document).ready(function () {
            console.log("jQuery is loaded and working!");

            // Manejar formulario de login
            $('#loginForm').on('submit', function (event) {
                event.preventDefault();
                var form = $(this);

                $.ajax({
                    url: form.attr('action'),  
                    type: form.attr('method'), 
                    data: form.serialize(), 
                    success: function (response) {
                        window.location.href = "{{ route('index') }}";
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors; 
                        var errorMessages = ""; 
                        $.each(errors, function (key, value) {
                        errorMessages += "<p>" + value[0] + "</p>";
                        });
                        $("#loginErrorMessages").html(errorMessages);
                    }
                });
            });

            // Manejar formulario de registro
            $('#registerForm').on('submit', function (event) {
                event.preventDefault();
                var form = $(this);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: form.serialize(),
                    success: function (response) {
                        window.location.href = "{{ route('auth') }}";
                    },
                    error: function (xhr) {
                        var errors = xhr.responseJSON.errors;
                        var errorHtml = '<ul>';
                        $.each(errors, function (key, value) {
                            errorHtml += '<li>' + value[0] + '</li>';
                        });
                        errorHtml += '</ul>';
                        $('#registerErrorMessages').html(errorHtml);
                    }
                });
            });
        });
    </script>
</body>
</html>