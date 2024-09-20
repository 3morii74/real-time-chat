<?php

namespace App\Livewire\Chat;

use App\Models\Message;
use Livewire\Component;

class ChatBox extends Component
{
    public $selectedConversation;
    public $body;
    public $loadedMessages;

    public $paginate_var = 10;

    protected $listeners = [
        'loadMore'
    ];

    public function loadMessages()
    {

        $userId = auth()->id();
        // #get count
        // $count = Message::where('conversation_id', $this->selectedConversation->id)
        //     ->where(function ($query) use ($userId) {

        //         $query->where('sender_id', $userId)
        //             ->whereNull('sender_deleted_at');
        //     })->orWhere(function ($query) use ($userId) {

        //         $query->where('receiver_id', $userId)
        //             ->whereNull('receiver_deleted_at');
        //     })
        //     ->count();

        #skip and query
        $this->loadedMessages = Message::where('conversation_id', $this->selectedConversation->id)
            // ->where(function ($query) use ($userId) {

            //     $query->where('sender_id', $userId)
            //         ->whereNull('sender_deleted_at');
            // })->orWhere(function ($query) use ($userId) {

            //     $query->where('receiver_id', $userId)
            //         ->whereNull('receiver_deleted_at');
            // })
            // ->skip($count - $this->paginate_var)
            // ->take($this->paginate_var)
            ->get();


        // return $this->loadedMessages;
    }

    public function sendMessage()
    {

        $this->validate(['body' => 'required|string']);


        $createdMessage = Message::create([
            'conversation_id' => $this->selectedConversation->id,
            'sender_id' => auth()->id(),
            'receiver_id' => $this->selectedConversation->getReceiver()->id,
            'body' => $this->body

        ]);


        $this->reset('body');
      // dd($createdMessage);
        #scroll to bottom
        // $this->dispatchBrowserEvent('scroll-bottom');


        #push the message
        $this->loadedMessages->push($createdMessage);


        // #update conversation model
        // $this->selectedConversation->updated_at = now();
        // $this->selectedConversation->save();


        // #refresh chatlist
        // $this->emitTo('chat.chat-list', 'refresh');

        #broadcast

        // $this->selectedConversation->getReceiver()
        //     ->notify(new MessageSent(
        //         Auth()->User(),
        //         $createdMessage,
        //         $this->selectedConversation,
        //         $this->selectedConversation->getReceiver()->id

        //     ));
    }

    public function mount()
    {
        $this->loadMessages();
    }
    public function render()
    {
        return view('livewire.chat.chat-box');
    }
}
