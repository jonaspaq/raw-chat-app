<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\MessageThread;
use App\Models\MessageThreadMember;

class MessageThreadController extends Controller
{
    /**
     * Get all message threads of the user.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = request()->user();

        $data = MessageThread::whereHas('threadMembers', function($q) use ($user){
                            $q->where('user_id', $user->id);
                            })
                ->with(['threadMembers' => function($q) use ($user){
                        $q->where('user_id' ,'<>', $user->id)
                        ->with('user');
                }])
                ->with('threadGroupDetails')
                ->get();


        return $data;
    }

    /**
     * Create a new one to one thread if there is no existing thread
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request;
        $data['user'] = $request->user();
        $thread = null;

        // Check if a thread between the two users exist
        // This is necessary in case they search both of them at the same time
        $check = MessageThread::whereHas('threadMembers', function ($query) use ($data) {
                        $query->whereExists(function ($query) use ($data) {
                            $query->where('user_id', $data->id)
                                ->where('user_id', $data->id);
                        });
                })
                ->whereDoesntHave('threadGroupDetails')
                // ->with(['threadMembers.user' => function($query) use($data) {
                //         $query->where('id', '<>' ,$data['user']->id);
                // }])
                ->get();

        if($check->count() >= 1) {
            return response()->json($check);
        }
        // Create thread if the check didn't find any thread
        else
        {
            $thread = MessageThread::create();
            $thread->threadMembers()->create(['user_id' => $data->id]);
            $thread->threadMembers()->create(['user_id' => $data['user']->id]);
            $thread = MessageThread::where('id',$thread->id)->with('threadMembers.user')->first();
        }

        return response()->json($thread);
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
     * Search for a person to person thread
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchThread(Request $request)
    {
        $text = $request->only('toSearch');
        $text['user'] = $request->user();

        $data = MessageThread::whereHas('threadMembers', function($q) use ($text){         // Select all thread
                                $q->whereHas('user', function($q) use ($text) {            // that has a user
                                    // can add more where LIKEs query here
                                    $q->where('name', 'LIKE', '%'.$text['toSearch'].'%');
                                });
                            })
                        ->whereDoesntHave('threadGroupDetails')                            // that is not a group
                        ->with(['threadMembers.user' => function($query) use($text){
                            $query->where('id', '<>', $text['user']->id);
                        }])
                        ->get();

        return response()->json($data);
    }

    /**
     * Search for a user that has no thread related with the logged in user
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchConnection(Request $request)
    {
        $text = $request->only('toSearch');
        $user = $request->user();

        $data = User::whereDoesntHave('threadMember.messageThread.threadMembers', function($query) use($user) {
                    $query->where('user_id', $user->id);
            })

            ->where('name', 'LIKE', '%'.$text['toSearch'].'%')
            ->where('id', '<>', $user->id)
            ->get();

        return response()->json($data);
    }

    /**
     * Search for a group thread
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function searchThreadGroup(Request $request)
    {
        $text = $request->only('toSearch');
        $user = $request->user();
        $data = MessageThread::has('threadGroupDetails')                            // Select a message thread that has group details
                        ->whereHas('threadMembers', function($query) use($user) {   // that has thread members
                            $query->where('user_id', $user->id);                    // where the authenticated user is also a member of
                        })
                        ->with('threadGroupDetails')
                        ->get();

        return response()->json($data);
    }
}
