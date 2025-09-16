<?php
// server/websocket-server.php
require __DIR__ . '/../vendor/autoload.php';


use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\WebSocket\WsServer;
use Ratchet\Server\Router;
use Ratchet\Http\HttpServer;
use React\Socket\SocketServer;


class SignalingServer implements MessageComponentInterface {
private $clients; // SplObjectStorage
private $rooms; // array roomId => array of connections


public function __construct() {
$this->clients = new \SplObjectStorage;
$this->rooms = [];
echo "Signaling server started\n";
}
public function onOpen(ConnectionInterface $conn) {
$this->clients->attach($conn);
$conn->room = null;
$conn->id = uniqid();
echo "New connection: {$conn->id}\n";
}


public function onMessage(ConnectionInterface $from, $msg) {
$data = json_decode($msg, true);
if (!$data) return;


$type = $data['type'] ?? '';
$room = $data['room'] ?? null;
$payload = $data['payload'] ?? null;

switch ($type) {
case 'join':
// join a room
if (!$room) return;
$from->room = $room;
if (!isset($this->rooms[$room])) $this->rooms[$room] = [];
$this->rooms[$room][$from->id] = $from;
echo "{$from->id} joined room {$room}\n";
break;


case 'signal':
// payload should have { to, data }
if (!$room || !isset($this->rooms[$room])) return;
$to = $data['to'] ?? null; // optional: target client id
// Broadcast to others in the same room
foreach ($this->rooms[$room] as $clientId => $clientConn) {
if ($clientConn === $from) continue;
// If `to` provided, only send to that client
if ($to && $clientId !== $to) continue;
$out = json_encode([
'type' => 'signal',
'from' => $from->id,
'payload' => $payload
]);
$clientConn->send($out);
}
break;
case 'leave':
$this->removeFromRoom($from);
break;


default:
// unknown
break;
}
}


public function onClose(ConnectionInterface $conn) {
echo "Connection closed: {$conn->id}\n";
$this->removeFromRoom($conn);
$this->clients->detach($conn);
}


public function onError(ConnectionInterface $conn, \Exception $e) {
echo "Error: {$e->getMessage()}\n";
$conn->close();
}
private function removeFromRoom(ConnectionInterface $conn) {
$room = $conn->room;
if (!$room) return;
if (isset($this->rooms[$room][$conn->id])) unset($this->rooms[$room][$conn->id]);
// if empty, remove room
if (empty($this->rooms[$room])) unset($this->rooms[$room]);
}
}


// Run server

$port = 8080; // WebSocket port
$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new SignalingServer()
        )
    ),
    $port
);

echo "WebSocket server listening on ws://0.0.0.0:{$port}\n";
$server->run();