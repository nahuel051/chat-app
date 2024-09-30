<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@300..700&display=swap" rel="stylesheet">
</head>

<body>
<div class="container" id="container">
        <div class="forms-container">
            <div class="signin-signup">
<form id="loginForm" class="sign-in-form" action="{{route('login')}}" method="post">
        @csrf
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit">Iniciar Sesión</button>
        <div id="loginErrorMessages"></div>
    </form>
    <form id="registerForm" class="sign-up-form" action="{{route('register')}}" method="post">
        @csrf
        <input type="text" name="name" placeholder="Nombre">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="password" name="password_confirmation" placeholder="Repetir contraseña">
        <button type="submit">Registrarse</button>
        <div id="registerErrorMessages"></div>
    </form>
    </div> <!--signin-signup" -->
    </div> <!--forms-container -->
    <div class="panels-container">
            <div class="panel left-panel">
                <div class="content">
                    <h1>Registrate!</h1>
                    <button class="btn transparent" id="sign-up-btn">Registrarse</button>
                </div> <!-- content -->
            </div> <!-- panel left-panel -->
            <div class="panel right-panel">
                <div class="content">
                    <h1>Bienvenido!</h1>
                    <button class="btn transparent" id="sign-in-btn">Iniciar Sesión</button>
                </div> <!-- content -->
            </div> <!--panel right-panel -->
        </div> <!--panels-container -->
    </div> <!--container -->
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
        const sign_in_btn = document.querySelector("#sign-in-btn");
        const sign_up_btn = document.querySelector("#sign-up-btn");
        const container = document.querySelector(".container");

        sign_up_btn.addEventListener("click", () => {
            container.classList.add("sign-up-mode");
        });

        sign_in_btn.addEventListener("click", () => {
            container.classList.remove("sign-up-mode");
        });
    </script>
</body>
</html>