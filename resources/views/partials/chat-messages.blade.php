<!-- Se define una vista parcial que se encarga de mostrar los mensajes de un chat. 
Esta vista parcial es clave para hacer que la interfaz de chat se actualice de manera dinámica. Cuando se actualizan los mensajes (usando AJAX, por ejemplo), esta vista se vuelve a renderizar para mostrar los nuevos mensajes sin necesidad de recargar toda la página.
$message->sender: Este fragmento de código accede a la relación sender que existe en el modelo Message, la cual hace referencia al usuario que envió el mensaje. -->
@foreach($messages as $message)
    <div class="chat-bubble {{ $message->sender->id == auth()->id() ? 'sent' : 'received' }}">
        <strong>{{ $message->sender->name }}</strong>
        <small>{{ $message->created_at->diffForHumans() }}</small>
        <p>{{ $message->message }}</p>
    </div>
    @endforeach

