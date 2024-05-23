<?php

namespace App\Http\Controllers;

use App\Traits\ApiResponser;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    use ApiResponser; // Trait for Generalizing the Response Methods.

    public function __construct()
    {
        $this->middleware('auth:api');
    }
}
