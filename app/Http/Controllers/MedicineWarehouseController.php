<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Medicine;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Resources\WareHouseResource;

class MedicineWarehouseController extends Controller
{






    public function index()
    {
        $warehouseId = Auth::user()->warehouse_id;

        $warehouse = Warehouse::find($warehouseId);

        if( $warehouse){
        $medicines = $warehouse->medicines()->withPivot('amount', 'final_date')->get();
        return response()->json($medicines);

        }
        return WareHouseResource::collection(
            $warehouse
        );
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'medicine_id' => 'required|exists:medicines,id',
            'amount' => 'required|integer|min:1',
            'final_date' => 'required|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }


        $warehouseId = Auth::user()->warehouse_id;
        $medicineId = $request->input('medicine_id');
        $amount = $request->input('amount');
        $finalDate = $request->input('final_date');

        $medicine = Medicine::find($medicineId);
        $warehouse = Warehouse::find($warehouseId);

        if (!$medicine || !$warehouse) {
            return response()->json(['error' => 'One or both resources not found.'], 404);
        }

        $record = $warehouse->medicines()->where('medicine_id', $medicineId)
        ->wherePivot('final_date', $finalDate)
        ->first();

    if ($record) {
        $pivotId = $record->pivot->id;
        $pivot = $warehouse->medicines()->newPivotStatement()->where('id', $pivotId)->first();
        $previos_amount = $pivot->amount ;
        if ($pivot) {
            $warehouse->medicines()
                ->newPivotStatement()
                ->where('id', $pivotId)
                ->update(['amount' =>  $previos_amount + $amount]);

            return response()->json(['message' => 'Amount updated successfully']);
        }
        return response()->json(['message' => 'Something went wrong']);
    }
        $warehouse->medicines()->attach($medicineId, [
            'amount' => $amount,
            'final_date' => $finalDate,
        ]);

        return response()->json(['message' => 'Data stored successfully.']);
    }


    public function destroy(Medicine $medicine)
    {

    }



    public function getAmount(Request $request)
    {
        $medicineId = $request->query('medicine_id');
        $warehouseId = Auth::user()->warehouse_id;

        $warehouse = Warehouse::find($warehouseId);
        $medicines = $warehouse->medicines->where('id', $medicineId);

        $total = 0;
        foreach ($medicines as $medicine) {
            $total += $medicine->pivot->amount;
        }

        return response()->json($total);
    }



    }


