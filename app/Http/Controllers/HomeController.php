<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use GatewayClient\Gateway;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
        Gateway::$registerAddress = '127.0.0.1:1238';
    }


    public function index()
    {
        return view('home');
    }

    public function init(Request $request)
    {
        // 绑定用户
        $this->bind($request);

        // 历史记录
        $this->history();

        // 进入聊天室
        $this->login();
    }

    private function bind($request)
    {
        $id = Auth::id();
        $client_id = $request->client_id;
        Gateway::bindUid($client_id, $id);
    }

    private function login()
    {
        $data = [
            'type' => 'say',
            'data' => [
                'avatar' => Auth::user()->avatar(),
                'name' => Auth::user()->name,
                'content' => '进入聊天室',
                'time' => date('Y-m-d H:i:s')
            ]
        ];
        Gateway::sendToAll(json_encode($data));
    }

    public function say(Request $request)
    {
        $data = [
            'type' => 'say',
            'data' => [
                'avatar' => Auth::user()->avatar(),
                'name' => Auth::user()->name,
                'content' => $request->input('content'),
                'time' => date('Y-m-d H:i:s')
            ]
        ];
        Gateway::sendToAll(json_encode($data));

        //存入数据库
        Message::create([
            'user_id' => Auth::id(),
            'content' => $request->input('content')
        ]);

    }

    private function history()
    {
        $data = ['type' => 'history'];
        $messages = Message::with('user')->orderBy('id', 'desc')->limit(5)
            ->get();
        $data['data'] = $messages->map(function ($item, $key) {
            return [
                'avatar' => $item->user->avatar(),
                'name' => $item->user->name,
                'content' => $item->content,
                'time' => $item->created_at->format('Y-m-d H:i:s')
            ];
        });
        //dump($data);
        Gateway::sendToUid(Auth::id(), json_encode($data));
    }
}
