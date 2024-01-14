<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{

    public function WareHousesSales(Request $request){
        //validate
        $request->validate([
            'dateFrom' => 'date' ,
            'dateTo' => 'date' ,
        ] , [
            'dateFrom' => 'dateFrom has to be a date type' ,
            'dateTo' => 'dateTo has to be a date type' ,
        ]);
        //fetsh database
        $orders = DB::table('orders')
            ->where('paid' , true)
            ->when($request->dateFrom , function($query) use ($request){
                $query->where('date' , '>=', $request->dateFrom);
            })
            ->when($request->dateTo , function($query) use ($request){
                $query->where('date' , '<=', $request->dateTo);
            })
            // ->when($request->status , function($query) use ($request){
            //     $query->where('status' , $request->status);
            // })
            ->join('warehouses' , 'orders.warehouse_id' , '=' , 'warehouses.id')
            ->get();

        $warehousesMap = [] ;
        foreach($orders as $o){
            if(!isset($warehousesMap[$o->id])){
                $warehousesMap[$o->warehouse_id] =
                [
                    'data' => [
                        'warehouse_id' => $o->warehouse_id ,
                        'name' => $o->name
                        ] ,
                    'sales'=>0
                ];
            }
            $warehousesMap[$o->warehouse_id]['sales'] += $o->total_price;
        }

        //sort and return
        return $this->SortBySales($warehousesMap);
    }

    public function MedicinesSales(Request $request){

        //validate
        $request->validate([
            'dateFrom' => 'date' ,
            'dateTo' => 'date' ,
        ] , [
            'dateFrom' => 'dateFrom has to be a date type' ,
            'dateTo' => 'dateTo has to be a date type' ,
        ]);

        //fetsh database
        $medicines_orders = DB::table('medicine_orders')
            ->when($request->dateFrom , function($query) use ($request){
                $query->where('created_at' , '>=', $request->dateFrom);
            })
            ->when($request->dateTo , function($query) use ($request){
                $query->where('created_at' , '<=', $request->dateTo);
            })
            ->get();

        $medicines = DB::table('medicines')->get() ;

        $paid_orders = DB::table('orders')
                ->where('paid' , true)
                ->where('status' , 'completed')
                ->get();

        $ordersPaidMap = [] ;
        foreach($paid_orders as $po){
            if(!isset($ordersPaidMap[$po->id])){
                $ordersPaidMap[$po->id] = false ;
            }
            $ordersPaidMap[$po->id] = $po->paid ;
        }
        //make the map
        $medicinesMap = [] ;
        foreach($medicines as $m){
                $medicinesMap[$m->id] = ['data' => $m , 'sales'=>0];
        }

        foreach($medicines_orders as $mo){
            if(isset($ordersPaidMap[$mo->order_id]))
               $medicinesMap[$mo->medicine_id]['sales'] += $mo->medicine_amount * $medicinesMap[$mo->medicine_id]['data']->price ;
        };

        //sort and return
        return $this->SortBySales($medicinesMap);
    }

    public function SortBySales($productList){
        return $productList = collect($productList)->sortByDesc('sales')->toArray();
    }
}
