<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Inertia\Inertia;

abstract class Controller extends BaseController
{
    use DispatchesJobs, ValidatesRequests;
    
    protected function inertia($view, $data = [])
    {
        return Inertia::render($view, $data);
    }
}
