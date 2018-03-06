<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('eurosport-message', function ($user) {
    return true;
});