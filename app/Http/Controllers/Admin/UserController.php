<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(public UserService $service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.users.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserRequest $request)
    {
        $user = $this->service->store($request->validated());

        if ($user) {
            return redirect()->back()->with('success', 'user added successfully');
        } else {
            return redirect()->back()->with('error', 'user storing failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        return view('admin.users.edit', ['user' => User::findOrFail($id)]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserRequest $request, string $id)
    {
        $user = $this->service->update($id, $request->validated());

        if ($user) {
            return redirect()->back()->with('success', 'user updated successfully');
        } else {
            return redirect()->back()->with('error', 'user updating failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        if ($this->service->destroy($id)) {
            return redirect()->route('admin.users.index')->with('success', 'user deleted successfully');
        } else {
            return redirect()->back()->with('error', 'user deleting failed');
        }
    }
}
