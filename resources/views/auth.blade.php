<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>

<body>
    <form action="{{route('register')}}" method="post">
        @csrf
        <input type="text" name="name" placeholder="Nombre">
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <input type="password" name="password_confirmation" placeholder="Repetir contraseña">
        <button type="submit">Registrarse</button>
    </form>
    <hr>
    <form action="{{route('login')}}" method="post">
        @csrf
        <input type="email" name="email" placeholder="Email">
        <input type="password" name="password" placeholder="Contraseña">
        <button type="submit">Iniciar Sesión</button>
    </form>
    @if ($errors->any())
        <div>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>