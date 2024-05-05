<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends ApiController
{
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
}
