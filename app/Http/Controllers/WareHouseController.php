<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWareHouseRequest;
use App\Http\Requests\UpdateWareHouseRequest;
use App\Http\Resources\MedicineResource;
use App\Http\Resources\WareHouseResource;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class WareHouseController extends Controller
{
    //CRUD
    public function index()
    {
        return WareHouseResource::collection(WareHouse::paginate()->items());
    }

    public function store(StoreWareHouseRequest $request)
    {
        $warehouse = WareHouse::create($request->validated());

        return new WareHouseResource($warehouse);
    }

    public function show(WareHouse $warehouse)
    {
        return new WareHouseResource($warehouse);
    }

    public function update(UpdateWareHouseRequest $request, WareHouse $warehouse)
    {
        $warehouse->update($request->validated());

        return new WareHouseResource($warehouse) ;
    }

    public function destroy(WareHouse $warehouse)
    {
        $warehouse->delete();

        return response()->json(null , 204) ;
    }
}
