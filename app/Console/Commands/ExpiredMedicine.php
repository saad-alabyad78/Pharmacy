<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use App\Http\Controllers\OrderController;
class ExpiredMedicine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:expired-medicine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $warehouses = Warehouse::all();
        $orderController = new OrderController();
        foreach ($warehouses as $warehouse) {

                $medicines = $warehouse->medicines;
                $weekAgo = Carbon::now()->subWeek();

                $records = $medicines->wherePivot('final_date', '>', $weekAgo)->get();
            
                foreach ($records as $record) {
                    // اشعار

                    // بعات لكل الادمن تبع هالمستودع
                $admins=User::where('role',"admin")->where('warehouse_id',$warehouse->id)->get();
                    foreach($admins as $admin)
                    {
                        
                        $title = "Expired Medicine";
                        $body = "Medicine: " . $record->name . "\n"
                        . "Final Date: " . $record->pivot->final_date . "\n"
                        . "Amount: " . $record->pivot->amount;                        $token = $admin->device_token;

                    $response = $orderController->sendPushNotification($title, $body, $token);



                    }
                }

                
            }
        }




}
