<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use AuthorizesRequests, ValidatesRequests;


    public function adminregister(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|digits:10|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            'warehouse_id' => 'required|exists:warehouses,id'
        ]);
        $user = new User();
        $user->phone = $validatedData['phone'];
        $user->password = bcrypt($validatedData['password']);
        $user->name = $validatedData['name'];
        $user->warehouse_id = $validatedData['warehouse_id'];
        $user->role ='Admin'   ;
        $user->save();

       // $token = $user->createToken('AuthToken')->plainTextToken;
        return response()->json(['message' => 'Registration successful', 'user' => $user]);
    }

    public function phregister(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required|digits:10|unique:users',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            'location' => 'required|string',
        ]);
        $user = new User();
        $user->phone = $validatedData['phone'];
        $user->password = bcrypt($validatedData['password']);
        $user->name = $validatedData['name'];
        $user->location = $validatedData['location'];

        $user->role ='Pharmacist'   ;


        $user->save();

        //$token = $user->createToken('AuthToken')->plainTextToken;
        return response()->json(['message' => 'Registration successful', 'user' => $user]);
    }

    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'phone' => 'required',
            'password' => 'required',
        ]);

        if (auth()->attempt(['phone' => $validatedData['phone'], 'password' => $validatedData['password']])) {
            $user = auth()->user();
            $token = $user->createToken('AuthToken')->plainTextToken;

            return response()->json(['message' => 'Login successful','$user->role' =>$user->role ,  'token' => $token]);
        } else {
            return response()->json(['message' => 'Invalid credentials'], 400);
        }
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logout successful']);
    }

    public function getAuthenticatedUserId()
    {
        return Auth::id();
    }


    public function test()
    {
        return 352;
    }

    public function test2()
    {
        return 3252;
    }
}
