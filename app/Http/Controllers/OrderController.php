<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Http\Requests\UpdateOrderRequest;
use App\Http\Resources\OrderResource;
use Illuminate\Support\Facades\Auth;
use App\Traits\HttpResponses;
use Illuminate\Http\Request;
use App\Models\Warehouse;
use App\Models\Medicine;

class OrderController extends Controller
{
    use HttpResponses;

    public function index(){
        if(Auth::user()->role =='Admin'){
        return OrderResource::collection(Order::where('warehouse_id',Auth::user()->warehouse_id)->latest()->paginate()->items());
    }
    return OrderResource::collection(Order::where('user_id',Auth::id())->latest()->paginate()->items());
    }


    public function all()
    {
        return OrderResource::collection(
            Order::where('user_id' , Auth::id())->latest()
        );
    }

    public function store(StoreOrderRequest $request)
    {

        $request->validated($request->all()) ;

        $order = Order::create([
            'user_id'=> Auth::id(),
            'status'=> 'pending',
            'total_price'=> $request->total_price,
            'date'=> $request->date,
            'paid'=>false,
        ]);

        foreach($request->medicines as $med){
            $order->medicines()->attach($med['id'] , ['medicine_amount' => $med['medicine_amount']]) ;



        }

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
       return new OrderResource($order);
    }

    public function update(UpdateOrderRequest $request, Order $order)
    {
        $order->update($request->all()) ;

        return new OrderResource($order);
    }


//   لما صيدلاني يحذف الطلب
    public function destroy(Order $order)
    {
        if($order->status=='pending'){
        $order->delete() ;
        return response()->json(" the order deleted succesfully ");
    }
        return response()->json(" the order alrady in progress ");
    }

    //   لما ادمن يحذف الطلب
    //to do
    public function admin_delete_Order($id)
    {
        $warehouse_id = Auth::user()->warehouse_id;
        $order = Order::find($id);

        if (!$order|| $order->warehouse_id != $warehouse_id ) {
            return response()->json("Order not for you.");
        }
        $order->medicines()->detach();

        $amountMedicin = $order->amount_medicin;
        $amountMedicin = json_decode($amountMedicin, false);
        $warehouse = Warehouse::find($warehouse_id);

        foreach ($amountMedicin as $data) {
            $medicine = Medicine::where('commercial_name', $data->medicine_name)->first();

            if ($medicine) {
                $medicine_id = $medicine->id;
                $medicin_warehouse = $warehouse->medicines()->where('medicine_id', $medicine_id)
                    ->where('warehouse_id', $warehouse_id)
                    ->wherePivot('final_date', $data->final_date)
                    ->first();

                if ($medicin_warehouse) {
                    $medicin_warehouse->pivot->amount += $data->amount;
                    $medicin_warehouse->pivot->save();
                }
            }
        }
        $order->delete();

        return response()->json("Order deleted successfully.");
    }

    public function take_order(Request $request)
    {

        $order = Order::findOrFail($request->input('order_id'));
        if($order->status!='pending')
        {
            return response()->json('order had alrady been taken');
        }
        $order->user_id = Auth::id();
        $order->warehouse_id = Auth::user()->warehouse_id;
        $order->status = 'in_progress';
        $order->save();

        $pivotData = $order->medicines()->get()->map(function ($medicine) {
            return $medicine->pivot;
        });

        $warehouseId = Auth::user()->warehouse_id;
        $returns = [];

        foreach ($pivotData as $pivot) {
            $medicines = Warehouse::find($warehouseId)->medicines()
                ->where('medicine_id', $pivot['medicine_id'])
                ->orderBy('final_date', 'asc')
                ->withPivot('amount', 'final_date')
                ->get();

            $totalAmount = 0;
            $requiredAmount = $pivot['medicine_amount'];



            foreach ($medicines as $medicine) {
                $totalAmount += $medicine->pivot->amount;
            }

            $medicinePointer = $medicines->first();
            $key = 1 ;
            if ($totalAmount >= $requiredAmount) {
                while ($requiredAmount > 0) {
                    $a = min($medicinePointer->pivot->amount, $requiredAmount);
                      $medicinePointer->pivot->amount -= $a;
                     $medicinePointer->pivot->save();
                    $requiredAmount -= $a;

                    if($a!=0){
                      $returns[] = ['amount' => $a, 'medicine_name' => $medicinePointer->commercial_name, 'final_date' => $medicinePointer->pivot->final_date];
                    }
                      $medicinePointer = $medicines->get($key);
                      $key = $key + 1 ;

            }} else {
                return response()->json(['message' => 'Not enough quantity available for medicine with name ' .  $medicinePointer->scientific_name]);
            }
        }
        $order->amount_medicin = $returns ;
        $order->save();
        return response()->json($returns);
    }


    public function status2on_the_way($id)
    {
        $order = Order::find($id);
        $warehouse_id = Auth::user()->warehouse_id;
        if (!$order|| $order->warehouse_id != $warehouse_id ) {
            return response()->json("Order not for you.");
        }

        $order->status = 'on_its_way';
        $order->save();
        return response()->json(" order 2 on_its_way .done!",200) ;
    }
    public function status2completed($id)
    {
        $order = Order::find($id);

        $warehouse_id = Auth::user()->warehouse_id;
        if (!$order|| $order->warehouse_id != $warehouse_id ) {
            return response()->json("Order not for you.");
        }
        $order->status = 'completed';
        $order->save();
        return response()->json(" order 2 completed .done!",200) ;

    }

    public function to_paid($id)
    {
        $order = Order::find($id);

        $warehouse_id = Auth::user()->warehouse_id;
        if (!$order|| $order->warehouse_id != $warehouse_id ) {
            return response()->json("Order not for you.");
        }
        $order->paid = 1;
        $order->save();
        return response()->json(" order is paid .done!",200) ;

    }

    public function getPendingOrder()
    {
       return OrderResource::collection(
        Order::where('status','pending')->paginate()->items()
    );
    }

    public function number_unread()
    {
        $num = 0;
        $orders = Order::where('status', 'pending')->get();

        foreach ($orders as $order) {
            $adminsRead = json_decode($order->admins_read, true);

            if ( !$adminsRead || !in_array(Auth::id(), $adminsRead)) {
                $num++;
            }
        }
        return response()->json(['num' => $num]);
    }



    public function read_notifications()
    {
        $orders = Order::where('status', 'pending')->get();

        foreach ($orders as $order) {
            $adminsRead = json_decode($order->admins_read, true);

            if (!$adminsRead || !in_array(Auth::id(), $adminsRead)) {
                $adminsRead[] = Auth::id();
                $order->admins_read = json_encode($adminsRead);
                $order->save();
            }
        }

        return response()->json(['message' => 'Notifications marked as read.']);
    }


}
