<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\MessageThread;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of message threads.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $threads = MessageThread::where(function($query) {
                $query->where('user1_id', Auth::id())
                    ->orWhere('user2_id', Auth::id());
            })
            ->with(['user1', 'user2', 'lastMessage'])
            ->latest('updated_at')
            ->paginate(20);
            
        return view('user.messages.index', compact('threads'));
    }
    
    /**
     * Display a message thread.
     *
     * @param  \App\Models\MessageThread  $thread
     * @return \Illuminate\View\View
     */
    public function show(MessageThread $thread)
    {
        // Ensure user is part of the thread
        if (!$thread->hasParticipant(Auth::id())) {
            abort(403, 'Unauthorized action.');
        }
        
        // Mark unread messages as read
        Message::where('thread_id', $thread->id)
            ->where('recipient_id', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
            
        $messages = Message::where('thread_id', $thread->id)
            ->with('sender')
            ->latest()
            ->paginate(50);
            
        return view('user.messages.show', compact('thread', 'messages'));
    }
    
    /**
     * Reply to a message thread.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\MessageThread  $thread
     * @return \Illuminate\Http\Response
     */
    public function reply(Request $request, MessageThread $thread)
    {
        // Ensure user is part of the thread
        if (!$thread->hasParticipant(Auth::id())) {
            abort(403, 'Unauthorized action.');
        }
        
        $request->validate([
            'content' => 'required|string|min:1'
        ]);
        
        // Create message
        $message = Message::create([
            'thread_id' => $thread->id,
            'sender_id' => Auth::id(),
            'recipient_id' => $thread->getOtherParticipant(Auth::id())->id,
            'content' => $request->content
        ]);
        
        // Update thread
        $thread->touch();
        
        return back()->with('success', 'Message sent successfully.');
    }
    
    /**
     * Create a new message thread.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'content' => 'required|string|min:1'
        ]);
        
        // Check if thread already exists
        $thread = MessageThread::between(Auth::id(), $request->recipient_id)->first();
        
        if (!$thread) {
            // Create new thread
            $thread = MessageThread::create([
                'user1_id' => Auth::id(),
                'user2_id' => $request->recipient_id
            ]);
        }
        
        // Create message
        Message::create([
            'thread_id' => $thread->id,
            'sender_id' => Auth::id(),
            'recipient_id' => $request->recipient_id,
            'content' => $request->content
        ]);
        
        return redirect()->route('messages.show', $thread)
            ->with('success', 'Message sent successfully.');
    }
}
