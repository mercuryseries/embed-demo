@extends('layouts.app')

@section('content')
    <h1>Wouhou 🎉</h1>

    <p style="color: green; font-weight: bold;">
        Thank you for your trust. See you to {{ $event->name }}.
    </p>
@endsection

@push('scripts.footer')
<script>
    var isFrame = window.parent !== window;

    if (isFrame) {
        window.parent.postMessage({
            messageName: 'orderComplete',
            orderId: Math.floor(Math.random() * 10) + 1,
            eventId: {{ $event->id }}
        }, '{{ request('parent') }}');
    }
</script>
@endpush
