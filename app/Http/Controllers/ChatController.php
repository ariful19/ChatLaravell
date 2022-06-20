<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function index()
    {
        return view('chat');
    }

    public function UpdateUser()
    {
        $bdy = request()->all();
        $row = DB::table("users")->whereRaw("name=? or name=?", [$bdy['id'], strtolower($bdy['id'])])->first();
        $resId = 0;
        if ($row == null) {
            $id = DB::selectOne('select max(id)+1 id from users')->id;
            DB::table('users')->insert(
                ['id' => $id, 'name' => $bdy['id'], 'email' => '', 'ConnectinId' => $bdy['connectionId']]
            );
            $resId = $id;
        } else {
            DB::table('users')->where('id', $row->Id)->update(
                ['ConnectinId' => $bdy['connectionId']]
            );
            $resId = $row->Id;
        }
        return ['id' => $resId];
    }
    public function GetUsers()
    {
        $users = DB::table("users")->get();
        return $users;
    }
    public function GetChats()
    {
        $from = (string)request()->query("from");
        $me = (string)request()->query("me");
        $chats = DB::table("chats")->whereRaw("FromUser = ? and ToUser = ? or FromUser = ? and ToUser = ?", [$from, $me, $me, $from])->get();
        return $chats;
    }
    public function FileUpload()
    {
        $from = request()->input('from');
        $to =  request()->input('to');
        $message = request()->input('message') != null ?  request()->input('message') : ' ';
        $file = request()->file('file');
        $fileName = $file->getClientOriginalName();
        // $fromUser = DB::table('users')->where('id', $from)->first();
        // $toUser = DB::table('users')->where('id', $to)->first();
        $id = DB::selectOne('select max(Id)+1 id from Chats')->id;
        $chatInfo = ['FromUser' => $from, 'ToUser' => $to, 'Id' => $id, 'Message' => $message, 'Time' => Carbon::now(), 'FileName' => $fileName];
        $chatInfo["FileName"] = $id . "_" .  $fileName;
        $file->move(public_path('files'), $chatInfo["FileName"]);
        DB::table("chats")->insert(['Id' => $id, 'FromUser' => $from, 'ToUser' => $to, 'Message' => $message, 'Time' => Carbon::now(), 'FileName' => $chatInfo["FileName"]]);
        return $chatInfo;
        // var chatInfo = { FromUser: from, ToUser: to, Id: id, Message: message, Time: new Date() };
        // if (file) {
        //     chatInfo["FileName"] = id + "_" + file.originalname;
        //     var dir = './client/files';
        //     if (!fs.existsSync(dir)) {
        //         fs.mkdirSync(dir, { recursive: true });
        //     }
        //     fs.copyFileSync(file.path, dir + "/" + chatInfo["FileName"]);
        // }
        // db.run(`INSERT INTO Chats
        //                 (Id,FromUser, ToUser, Message, Time, FileName) VALUES( ?,?,?,?,?,?)`, [id, from, to, message, new Date(), chatInfo["FileName"]]);
        // wss.clients.forEach(c => {
        //     if (c.id == parseInt(toUser.ConnectinId)) {
        //         c.send(JSON.stringify({ type: 'chat', fromUser: fromUser.Name, chat: chatInfo }));
        //     }
        // });
        // return res.json(chatInfo);
    }
}
