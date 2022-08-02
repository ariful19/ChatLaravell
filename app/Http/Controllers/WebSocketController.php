<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\Date;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Illuminate\Support\Facades\DB;

class WebSocketController extends Controller implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
        $conn->send(json_encode(['type' => 'connectionId', 'id' => $conn->resourceId]));
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $json = json_decode($msg, true);

        switch ($json['type']) {
            case "chat":
                $this->sendChat($json);
                break;
            case "notification":
                echo $msg;
                $this->sendNotification($json);
                break;
            case "chatFile":
                $chat = DB::table("chats")->where("id", $json["Id"])->first();
                $fromUser = DB::table('Users')->where('id', $chat->FromUser)->first();
                $toUser = DB::table('Users')->where('id', $chat->ToUser)->first();
                foreach ($this->clients as $client) {
                    if ($client->resourceId == $toUser->ConnectinId) {
                        $client->send(json_encode(['type' => 'chat', 'fromUser' => $fromUser->Name, 'chat' => $chat]));
                    }
                }
                break;
            case "userUpdated":
                foreach ($this->clients as $client) {
                    $client->send(json_encode(['type' => 'userUpdated']));
                }
                break;
            default:
                break;
        }
    }
    private function sendChat($json)
    {
        $cid = $json['toClientId'];
        $fileName = $json['fileName'] != null ? $json['fileName'] : '';
        $fromUser = DB::table('Users')->where('id', $json['from'])->first();
        $id = DB::selectOne('select max(Id)+1 id from Chats')->id;
        DB::table("chats")->insert(['Id' => $id, 'FromUser' => $json['from'], 'ToUser' => $json['to'], 'Message' => $json['message'], 'Time' => Carbon::now(), 'FileName' => $fileName]);
        $chatInfo = ['FromUser' => $json['from'], 'ToUser' => $json['to'], 'Id' => $id, 'Message' => $json['message'], 'Time' => Carbon::now(), 'FileName' => $fileName];
        foreach ($this->clients as $client) {
            if ($client->resourceId == $cid) {
                $client->send(json_encode(['type' => 'chat', 'fromUser' => $fromUser->Name, 'chat' => $chatInfo]));
            }
        }
    }
    private function sendNotification($json)
    {
        $cid = $json['toClientId'];
        $id = DB::selectOne('select max(Id)+1 id from Notification')->id;
        $notifInfo = ['id' => $id, 'ToUser' => $json['toUserId'], 'Message' => $json['message'], 'Time' => Carbon::now()];
        DB::table("Notification")->insert($notifInfo);
        foreach ($this->clients as $client) {
            if ($client->resourceId == $cid) {
                $client->send(json_encode(['type' => 'notification', 'data' => $notifInfo]));
            }
        }
    }
    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }
}
