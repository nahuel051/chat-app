<a href="{{route('index')}}">Inicio</a>
<a href="{{route('search')}}">Buscar Usuarios</a>
<form action="{{ route('logout') }}" method="POST">
    @csrf
    <button type="submit">Cerrar Sesión</button>
</form>
<form action="{{ route('delete.user') }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar tu cuenta? Esta acción no se puede deshacer.')">
    @csrf
    @method('DELETE')
    
    <button type="submit">Eliminar cuenta</button>
</form>