<?php
namespace Casino\Classes\Socket;

use Casino\Classes\Socket\Base\BaseSocket;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Ratchet\ConnectionInterface;

class Socket extends BaseSocket
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        //Store the new connection to send messages to later


        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
        //$host = $conn->WebSocket;
        User::where('id', Auth::id())->update(['u_socket' => $conn->resourceId]);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $numRecv = count($this->clients) - 1;
        foreach ($this->clients as $client) {
            if($from !== $client) {
                //The sender is not the receiver, send to each client connection
                $client->send($msg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        //The connection is closed, remove it, as we can no longer send it message
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occured: {$e->getMessage()}\n";
        $conn->close();
    }

}