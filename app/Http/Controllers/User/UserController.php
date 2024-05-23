<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Mail\UserCreated;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserController extends ApiController
{

    public function __construct()
    {
        $this->middleware('client.credentials')->only(['store', 'resend']); // this middleware is to protect some routes from being accessed by any user who dosn't authentecated and verified.
        // To have access to the routes protected by the middleware client.credentials, we have first to exicute this line in the cmd: (php artisan passport:client) this will generate client_id and client_secret token, then go to the postman and navigate to the route oauth/token, now we have to pass some arguments with the body, 1- 'grant_type' => 'client_credentials', 2- 'client_id' => '3' (the generated client_id), 3- 'client_secret' => 'j3noafBmfkwGS3aENWhESesOlQSh8lE8XQKTEAZm' (the generated client_secret) , send the post request, this will generate a Bearer access_token, copy it and past it with every route have the middleware 'client.credentials' that require this token (by example /categories route).

        $this->middleware('auth:api')->except(['store', 'verify', 'resend']);
        // This is a semilar approch to the generate client.credentials token, but the first deffirence is the user who have a password access_token he can access the poth routes protected by the 'client.credentials' middleware and the 'auth:api' middleware, the second deffirence here is the user have to pass his username(email) and the password with the generate token (oauth/token) post request. dont forget to add this line: (Passport::enablePasswordGrant()) to the AuthServiceProvider file.
        // To have access to the routes protected by the middleware auth:api, we have first to exicute this line in the cmd: (php artisan passport:client --password) this will generate client_id and client_secret token, then go to the postman and navigate to the route oauth/token, now we have to pass some arguments with the body, 1- 'grant_type' => 'password', 2- 'client_id' => '4' (the generated client_id), 3- 'client_secret' => 'pxsWwbJVCxQ4nJlc0Ka71e1zvRRfxszRD7Z3mdyw' (the generated client_secret), 4- 'username' => 'monty66@example.com', 5- 'password' => 'password'(the eamil passord) , send the post request, this will generate a Bearer access_token and refresh_token, copy the access_token it and past it with every route have the middleware 'api:auth' that require this token (by example /users route).
        
        $this->middleware('validate.resource.input:' . UserResource::class)->only(['store', 'update']); // this middleware is for applying the validation on the the resource attributes not to on the original attributes of the model (like 'identifier' insted of 'id' etc..)
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return $this->showAll($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['password'] = bcrypt($request->password);
        $data['verified'] = User::UNVERIFIED_USER;
        $data['verification_token'] = User::generateVerificationCode();
        $data['admin'] = User::REGULAR_USER;

        $user = User::create($data);

        return $this->showOne($user, 201); // 201 => data created successfuly.
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return $this->showOne($user);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id, // to except this user email to not be unique, in case if he dont want to change his email. 
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::ADMIN_USER . ',' . User::REGULAR_USER,
        ];

        $this->validate($request, $rules);

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email') && $user->email != $request->email) { // if the user want to change his email, he will have to verifiy the new email
            $user->verified = User::UNVERIFIED_USER; // mark the user as UNVERIFIED_USER.
            $user->verification_token = User::generateVerificationCode(); // generate a new verification code.
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->isVerified()) {
                return $this->errorResponse('Only verified users can modify the admin field', 409); // 409 Conflict code, the errorResponse is a function from the ApiResponser Trait.
            }

            $user->admin = $request->admin;
        }

        if (!$user->isDirty()) { // ->isDirty() means the user has changed (one argument at least in the user model has changed).
            return $this->errorResponse('You need to specify a different value to update', 422); // 422 Unprocessable Content, the errorResponse is a function from the ApiResponser Trait.
        }

        $user->save();

        return $this->showOne($user);
    }
    

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        $user->delete();

        return response(status: 204);  // dont send any message when delete anything, just return the status 204 (no content).
    }

    public function verifyUser($token)
    {
        $user = User::where('verification_token', $token)->firstOrFail();

        $user->verified = User::VERIFIED_USER;
        $user->verification_token = null;

        $user->save();

        return $this->showMessage('The account has been verified succesfully');
    }

    public function resendVerificationCode(User $user)
    {
        if ($user->isVerified()) {
            return $this->errorResponse('This user is already verified', 409);
        }

        retry(5, function() use ($user) { // retry() helper is to deal with failed opperations, its trying to resending the email 5 times before throw an error, and its wait for 100 ms before the next try.
            Mail::to($user)->send(new UserCreated($user));
        }, 100);

        return $this->showMessage('The verification email has been resend');
    }
}
