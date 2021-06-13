@extends('layouts.app')

@section('content')
    <h1>{{ $events->count() }} Events</h1>

    @foreach($events as $event)
        <article>
            <h2><a href="{{ route('events.show', $event) }}">{{ $event->name }}</a></h2>
            <p>{{ $event->description }}</p>

            <ul>
                <li>Location: {{ $event->location }}</li>
                <li>Price: {{ $event->price }}</li>
                <li>Date: {{ $event->starts_at }}</li>
            </ul>
        </article>
    @endforeach
@endsection
