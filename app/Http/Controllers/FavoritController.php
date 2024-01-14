<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\StoreFavoriteRequest;
use Illuminate\Support\Facades\Auth;

class FavoritController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favoriteMedicines()->get();

        $favorites->transform(function ($item) {
            unset($item->category_id);
            unset($item->company_id);
            unset($item->created_at);
            unset($item->updated_at);
            unset($item->pivot);
            return $item;
        });

        return response()->json($favorites, 200);
    }


    public function store(StoreFavoriteRequest $request){

       if( !Auth::user()->favoriteMedicines->contains($request->medicin_id)){
        Auth::user()->favoriteMedicines()->attach($request->medicin_id);
        return response()->json(['message' => 'Favorite medicine added successfully'], 200);}

     return response()->json(['message' => ' medicine alrady added '], 200);
    }


    public function destroy($medicin_id)
    {
        Auth::user()->favoriteMedicines()->detach($medicin_id);
        return response()->json(['message' => 'Favorite medicine removed successfully'], 200);
    }



}
