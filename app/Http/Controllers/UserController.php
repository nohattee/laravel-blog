<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    /**
     * Create the controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->authorizeResource(User::class, 'user');
    }

    /**
     * Display a listing of the resource.
     *
     * @return UserCollection
     */
    public function index(Request $request)
    {
        $users = User::query();
        if ($request->input('filters')) {
            $filters = json_decode($request->input('filters'), true);
            $users = $users->filter($filters);
        }

        $users = $users->with('roles');

        if ($request->input('page')) {
            $pageSize = $request->input('page_size', 10);
            $users = $users->paginate($pageSize);
        } else {
            $users = $users->get();
        }
        return new UserCollection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate(User::$rules);
        $user = User::create($validated);
        return response()->json([
            'data' => $user,
            'message' => 'create_success',
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate(User::$rules);
        $user->update($validated);
        return response()->json([
            'message' => 'update_success',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();
        return response()->json([
            'message' => 'delete_success',
        ]);
    }
}
