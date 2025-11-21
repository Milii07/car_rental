<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Pusher Test</title>
    <script src="https://js.pusher.com/8.4.0/pusher.min.js"></script>
</head>

<body>
    <h1>Pusher Test</h1>
    <p>
        Try publishing an event to channel <code>my-channel</code> with event name <code>my-event</code>.
    </p>

    <script>
        Pusher.logToConsole = true;

        var pusher = new Pusher('d0652d5ed102a0e6056c', {
            cluster: 'eu'
        });

        var channel = pusher.subscribe('my-channel');


        channel.bind('my-event', function(data) {
            console.log("Event received:", data);
            alert("Event received:\n" + JSON.stringify(data));
        });
    </script>
</body>

</html>