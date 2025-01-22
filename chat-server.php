<?php
require 'vendor/autoload.php';

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class ChatServer implements MessageComponentInterface {
    protected $clients;
    protected $users = [];

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        $data = json_decode($msg, true);

        if ($data['type'] === 'message') {
            // Store the message in the database
            include 'includes/db.php';
            $stmt = $conn->prepare("INSERT INTO messages_with_attachments (sender_id, receiver_id, message, sent_at, is_read) VALUES (?, ?, ?, NOW(), 0)");
            $stmt->bind_param("iis", $data['sender_id'], $data['receiver_id'], $data['message']);
            $stmt->execute();
            $stmt->close();

            // Send the message to all clients
            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'type' => 'message',
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id'],
                    'message' => $data['message'],
                    'time' => date("h:i A"),
                    'seen' => 0
                ]));
            }
        } elseif ($data['type'] === 'typing') {
            foreach ($this->clients as $client) {
                if ($client !== $from) {
                    $client->send(json_encode([
                        'type' => 'typing',
                        'user_id' => $data['user_id']
                    ]));
                }
            }
        } elseif ($data['type'] === 'seen') {
            include 'includes/db.php';
            $stmt = $conn->prepare("UPDATE messages_with_attachments SET is_read = 1 WHERE sender_id = ? AND receiver_id = ?");
            $stmt->bind_param("ii", $data['sender_id'], $data['receiver_id']);
            $stmt->execute();
            $stmt->close();

            foreach ($this->clients as $client) {
                $client->send(json_encode([
                    'type' => 'seen',
                    'sender_id' => $data['sender_id'],
                    'receiver_id' => $data['receiver_id']
                ]));
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} closed\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "Error: {$e->getMessage()}\n";
        $conn->close();
    }
}

$server = \Ratchet\Server\IoServer::factory(
    new \Ratchet\Http\HttpServer(
        new \Ratchet\WebSocket\WsServer(
            new ChatServer()
        )
    ),
    8080
);

echo "WebSocket server started on port 8080...\n";
$server->run();
