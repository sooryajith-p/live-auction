<?php

namespace App\Events;

use App\Models\Bid;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class BidPlaced implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $bid;
    public $user;
    public $productId;

    public function __construct(Bid $bid)
    {
        $this->bid = $bid;
        $this->user = $bid->user;
        $this->productId = $bid->product_id;
    }

    public function broadcastOn(): Channel
    {
        return new Channel('product.' . $this->productId);
    }

    public function broadcastAs(): string
    {
        return 'BidPlaced';
    }
}
