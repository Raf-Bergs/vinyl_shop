<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    // Edit user password
    public function edit()
    {
        return view('user.password');
    }

    // Update and encrypt user password
    public function update(Request $request)
    {
        // Validate $request

        // Update encrypted user password in the database

        // Flash a success message to the session

        // Redirect to previous page
        return back();
    }
}
