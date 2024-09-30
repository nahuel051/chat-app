@foreach($users as $user)
    <li>
        <a href="{{ route('index', ['user_id' => $user->id]) }}">{{ $user->name }}</a>
    </li>
@endforeach