<?php

namespace App\Events;

use App\Models\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\InteractsWithSockets;

class MessageSent implements ShouldBroadcast
{
    use InteractsWithSockets, SerializesModels;

    public $message;
    public $user;
    public $productId;

    public function __construct(Message $message)
    {
        $this->message = $message->load('user');
        $this->user = $this->message->user;
        $this->productId = $message->product_id;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('product.' . $this->productId);
    }

    public function broadcastAs(): string
    {
        return 'MessageSent';
    }

    public function broadcastWith(): array
    {
        return [
            'message' => [
                'id' => $this->message->id,
                'user_id' => $this->message->user_id,
                'message' => $this->message->message,
                'product_id' => $this->message->product_id,
                'created_at' => $this->message->created_at,
                'user' => [
                    'id' => $this->user->id,
                    'name' => $this->user->name,
                    'email' => $this->user->email,
                    'email_verified_at' => $this->user->email_verified_at,
                    'created_at' => $this->user->created_at,
                    'updated_at' => $this->user->updated_at,
                ],
            ],
            'user' => [
                'id' => $this->user->id,
                'name' => $this->user->name,
                'email' => $this->user->email,
                'email_verified_at' => $this->user->email_verified_at,
                'created_at' => $this->user->created_at,
                'updated_at' => $this->user->updated_at,
            ],
            'productId' => $this->productId,
        ];
    }
}
