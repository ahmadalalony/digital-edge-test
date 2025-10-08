<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserViewController extends Controller
{
    public function __construct()
    {
        // $this->middleware(['auth', 'role:Admin']);
    }

    public function index()
    {
        $users = User::all();
        return view('dashboard.users.index', compact('users'));
    }

    public function edit($id)
    {
        return view('dashboard.users.edit', compact('id'));
    }
}