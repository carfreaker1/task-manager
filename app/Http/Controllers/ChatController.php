<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\NewPrivateMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class ChatController extends Controller
{
    public function index()
    {
        $users = \App\Models\User::where('id', '!=', auth()->id())->get();
        return view('messages.chat', compact('users'));
    }

    public function sendMessage(Request $request)
    { 
        $message = NewPrivateMessage::create([
            'from_id' => auth()->id(),
            'to_id'   => $request->to_id,
            'message' => $request->message,
        ]);

        broadcast(new MessageSent($message))->toOthers();
        return response()->json($message->load('sender'));

   
    }

      public function fetchMessages($id)
    {
        $messages = NewPrivateMessage::where(function($q) use ($id) {
            $q->where('from_id', auth()->id())->where('to_id', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('from_id', $id)->where('to_id', auth()->id());
        })->with('sender')->orderBy('created_at')->get();

        return response()->json($messages);
    }
}
