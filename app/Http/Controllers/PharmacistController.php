<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class PharmacistController extends Controller
{
    public function index()
    {
        $pharmacists = User::where('role', 'Pharmacist')->get();

        $pharmacists = $pharmacists->map(function ($pharmacist) {
            return [
                'id' => $pharmacist->id,
                'name' => $pharmacist->name,
                'location'   => $pharmacist->location,
                'phone'   => $pharmacist->phone,
                      ];
        });

        return response()->json($pharmacists);
    }

}
