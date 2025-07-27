<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('product.{productId}', function ($user, $productId) {
    return true;
});