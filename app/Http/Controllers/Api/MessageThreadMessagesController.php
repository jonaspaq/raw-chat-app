<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MessageThreadMessages;

class MessageThreadMessagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Create a new message
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $details = $request->only('body', 'thread');
        $user = $request->user();

        $message = new MessageThreadMessages;
        $message->body = $details['body'];
        $message->message_thread_id = $details['thread'];
        $message->user_id = $user->id;
        $message->time_sent = now();
        $message->save();

        return response()->json($message);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     * Fetch all messages the belongs to a thread
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function fetchMessages($id)
    {
        $data = MessageThreadMessages::where('message_thread_id', $id)
                            ->get();

        return response()->json($data);
    }
}
