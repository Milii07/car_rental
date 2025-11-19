<?php
require __DIR__ . '/../../../vendor/autoload.php';

$pusher = new Pusher\Pusher(
    'd0652d5ed102a0e6056c',
    '90c034773a5a9e225f20',
    '2079882',
    [
        'cluster' => 'eu',
        'useTLS' => true
    ]
);

$data = ['message' => 'Hello from PHP!'];
$pusher->trigger('my-channel', 'my-event', $data);

echo "Event sent!";
