<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function store(Event $event)
    {
        return view('payments.success', compact('event'));
    }
}
