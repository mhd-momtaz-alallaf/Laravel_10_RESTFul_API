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

        /* 
            To have access to the routes protected by the middleware auth:api, 
            We have first to exicute this line in the cmd: (php artisan passport:client --password) 
            This will generate client_id and client_secret token, then go to the postman and navigate to the route oauth/token, 
            Now we have to pass some arguments with the body: 
            1- 'grant_type' => 'password', 
            2- 'client_id' => '4' (the generated client_id), 
            3- 'client_secret' => 'pxsWwbJVCxQ4nJlc0Ka71e1zvRRfxszRD7Z3mdyw' (the generated client_secret), 
            4- 'username' => 'monty66@example.com', 
            5- 'password' => 'password'(the eamil passord), 

            Send the post request, this will generate a Bearer access_token and refresh_token, 
            Copy the access_token it and past it with every route have the middleware 'api:auth' that require this token (by example /users route).

            Note: the user who have a password access_token he can access the poth routes protected by the 'client.credentials' middleware and the 'auth:api' middleware,
            
            Dont forget to add this line: (Passport::enablePasswordGrant()) to the AuthServiceProvider file.
        */
    }
}
