<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Auth;
use App\Models\Lead;
use App\Events\LeadUpdated;

Broadcast::channel('leads.{id}', function ($user, $id) {
    return true; // No authorization needed, allow all users
});

Broadcast::channel('my-channel', function ($user) {
    return true; // Customize as needed for your use case
});
