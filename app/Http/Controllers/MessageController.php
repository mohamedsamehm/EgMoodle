<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMessageRequest;
use App\Http\Requests\UpdateMessageRequest;
use App\Models\User;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\MessageSend;

class MessageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $contacts = User::latest()->where('id', '!=', Auth::user()->id)->with('role')->get();
        $chats = Message::join('users', 'users.id', '=', 'messages.from')
        ->where('messages.from', Auth::user()->id)
        ->orWhere('messages.to', Auth::user()->id)
        ->orderBy('messages.created_at', 'DESC')
        ->get(['users.id as user_id', 'users.*', 'messages.id as message_id', 'messages.*'])
        ->groupBy(function($data) {
            return $data->user_id;
        });
        return response()->json([
			'status' => 200,
			'contacts' => $contacts,
			'chats' => $chats,
		], 200);
    }
    public function messages()
    {
        $chats = Message::join('users', 'users.id', '=', 'messages.from')->where('to', Auth::user()->id)->get();
        return response()->json([
			'status' => 200,
			'chats' => $chats,
		], 200);
    }
    public function getChat($id)
    {
        $chat = Message::where(function($q) use($id) {
            $q->where('from', Auth::user()->id);
            $q->where('to', $id);
        })->orWhere(function($q) use($id) {
            $q->where('from', $id);
            $q->where('to', Auth::user()->id);
        })->get();
        return response()->json([
			'status' => 200,
			'chat' => $chat,
		], 200);
    }
    public function sendMessage(Request $request)
    {
        $messages = Message::create([
            'message' => $request->message,
            'from' => $request->from,
            'to' => $request->to,
            'type' => 0, 
        ]);
        $name = $request->fullName;
        broadcast(new MessageSend($messages, $name));
        return response()->json($messages, 201);
    }
    public function updateType($id)
    {
        $messages = Message::where('from', '=', $id)->update(['type' => 1]);
        return response()->json($messages, 201);
    }
    public function changeStatus(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if($request->status == "online") {
            $status = 1;
        } else if($request->status == "busy") {
            $status = 2;
        } else if($request->status == "away") {
            $status = 3;
        } else if($request->status == "offline") {
            $status = 4;
        } else {
            $status = 0;
        }
        $user->status = $status;
        $user->save();
        return response()->json($user, 200);
    }
    public function destroy(Request $request)
    {
        $id = $request->id;
        $chat = Message::where(function($q) use($id) {
            $q->where('from', Auth::user()->id);
            $q->where('to', $id);
        })->orWhere(function($q) use($id) {
            $q->where('from', $id);
            $q->where('to', Auth::user()->id);
        })->delete();
    }
}
