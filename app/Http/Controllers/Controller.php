<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function flashSuccess(string $message): void
    {
        flash()->success($message); // !! Not Working with flash() helper, using session()->flash() instead
        session()->flash('app_success', $message);
    }

    protected function flashError(string $message): void
    {
        flash()->error($message);
        session()->flash('app_error', $message);
    }
}
