<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected static function mylog ($level, $message)
    {
        $message = (Auth::check() ? Auth::user()->name : 'Аноним') . '['. $_SERVER["REMOTE_ADDR"]. '] ' . $message;
        if ($level === 'info') Log::info($message);
        if ($level === 'warning') Log::warning($message);
        if ($level === 'alert') Log::alert($message);
    }
}
