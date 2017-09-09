<?php
namespace Casino\Classes\Socket;

use Casino\Classes\Socket\Base\BaseSocket;
use DateTime;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ratchet\ConnectionInterface;
use Devristo\Phpws\Server\WebSocketServer;

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
        $socket = json_encode(['connection' => $conn->resourceId]);
        $conn->send($socket);

    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        $array = [];
        foreach ($data as $key => $item) {
            if($key != 'user' and $key != 'answer') {
                $array = array_merge($array, [$item]);
            }
        }
        var_dump($array);
        foreach ($this->clients as $client) {
            if(in_array($client->resourceId, $array)) {
                //The sender is not the receiver, send to each client connection
                $client->send($msg);
            }
        }
    }


    public function onClose(ConnectionInterface $conn)
    {
        //The connection is closed, remove it, as we can no longer send it message
        $this->clients->detach($conn);
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occured: {$e->getMessage()}\n";
        $conn->close();
    }

}