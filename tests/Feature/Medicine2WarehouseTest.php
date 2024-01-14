<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\Medicine;
use App\Models\User;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class Medicine2WarehouseTest extends TestCase
{
    use RefreshDatabase;

    protected $admin , $warehouse , $medicine , $category , $company;

    public function setUp():void
    {
        parent::setUp() ;

        $this->admin = User::factory()->create([
            'name' => 'hasn' ,
            'phone' => '0987654321' ,
            'role' => 'Admin' ,
        ]);

        $this->company = Company::factory()->create();
        $this->category = Category::factory()->create();

        $this->warehouse = Warehouse::factory()->create();
        $this->medicine = Medicine::factory()->create([
            'category_id' => $this->category->id ,
            'company_id' => $this->company->id ,
        ]);

        $this->admin->warehouse_id = $this->warehouse->id;
        $this->admin->save();

    }

    public function test_store_m_2_w_table()
    {
        $amount = 1 ;
        $final_date = Carbon::now()->addYear()->format('Y-m-d');

        $this->assertDatabaseMissing('medicine_warehouse' , [
            'medicine_id' => $this->medicine->id ,
            'warehouse_id' => $this->warehouse->id ,
            'amount' => $amount ,
            'final_date' => $final_date ,
        ]);

        $response = $this->actingAs($this->admin)->postJson('api/admin/store/medicine' , [
            'medicine_id' => $this->medicine->id ,
            'amount' => $amount ,
            'final_date' => $final_date ,
        ]);


        $response->assertStatus(201) ;

        $this->assertDatabaseHas('medicine_warehouse' , [
            'medicine_id' => $this->medicine->id ,
            'warehouse_id' => $this->warehouse->id ,
            'amount' => $amount ,
            'final_date' => $final_date ,
        ]);
    }
}
