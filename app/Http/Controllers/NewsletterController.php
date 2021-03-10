<?php

namespace App\Http\Controllers;

use App\Console\Commands\SendVerifyEmailCommand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class NewsletterController extends Controller
{
    public function send()
    {
        Artisan::call(SendVerifyEmailCommand::class);

        return response()->json([
            'data' => 'Todo ok'
        ]);
    }
}
