<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Warehouse;

class AdminController extends Controller
{
    public function index()
    {
        $admins = User::where('role', 'Admin')->get();

        foreach ($admins as $admin) {
            $admin->warehouse = Warehouse::find($admin->warehouse_id)->name;
        }

        return response()->json($admins);
    }
}
