@extends('layouts.app')

@section('content')
    <article>
        <h1>{{ $event->name }} at {{ $event->location }}</h1>
        <h3><small>{{ $event->starts_at }}</small></h3>
        <p>{{ $event->description }}</p>

        <form action="{{ route('payments.store', ['event' => $event, 'parent' => request('parent')]) }}" method="POST">
            <table>
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price (USD)</th>
                        <th>Quantity</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($event->tickets as $ticket)
                <tr>
                    <td>{{ $ticket->name }}</td>
                    <td>{{ $ticket->price_in_dollars }}</td>
                    <td>
                        <select>
                            @foreach(range(0, 10) as $i)
                            <option value="{{ $i }}">{{ $i }}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
                </tbody>
            </table>

            <button type="submit">Buy Tickets</button>
        </form>
    </article>

    <p>
        <code><pre>
{{ '<div id="eventz-widget-container-'. $event->id .'"></div>

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.3.2/iframeResizer.min.js"></script>

<script src="'.config('app.url') .'/js/eventz-widget.js"></script>

<script type="text/javascript">
var exampleCallback = function () {
    alert("Order complete!");
};

window.EventzWidget.setup({
    eventId: '. $event->id .',
    iframeContainerId: "eventz-widget-container-'. $event->id .'",
    onOrderComplete: exampleCallback,
});
</script>' }}
        </pre></code>
    </p>

    <p>
        <a href="{{ route('home') }}">&larr; All Events</a>
    </p>
@endsection

@push('scripts.footer')
<script src="https://code.jquery.com/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/4.2.11/iframeResizer.contentWindow.min.js"></script>
<script>
    var isFrame = window.parent !== window;

    if (isFrame) {
        window.parent.postMessage({
            messageName: 'widgetRenderComplete',
            eventId: {{ $event->id }}
        }, '{{ request('parent') }}');
    }
</script>
@endpush
