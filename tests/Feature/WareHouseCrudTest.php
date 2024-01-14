<?php

namespace Tests\Feature;

use App\Models\Warehouse;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WareHouseCrudTest extends TestCase
{
    use RefreshDatabase ;

    public function test_warehouse_index_return_paginated_recourds_currectly()
    {
        Warehouse::factory(16)->create() ;

        $response = $this->get('api/warehouse');

        $response->assertStatus(200);
        $response->assertJsonCount(15 , 'data');
        $response->assertJsonPath('meta.last_page' , 2) ;
    }

    public function test_store_WareHouse()
    {
        WareHouse::truncate();

        $name = "saad" ;

        $response = $this->postJson('api/warehouse' , ['name' => $name]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('warehouses', ['name' => $name ]);
    }

    public function test_show_WareHouse()
    {
        WareHouse::truncate();
        $name = "saad" ;

        $dose_not_exist_id = 1 ;
        $response = $this->getJson('api/warehouse/'.$dose_not_exist_id);
        $response->assertStatus(404) ;

        $warehouse = WareHouse::factory()->create(['name' => $name]);

        $response = $this->getJson('api/warehouse/ '.$warehouse->id ); 
        
        $response->assertStatus(200);
        $response->assertJsonPath('data.name' , $name);
    }

    public function test_update_WareHouse()
    {
        WareHouse::truncate();

        $oldname = "hela" ;
        $newname = "saad" ;

        $warehouse = WareHouse::factory()->create(['name' => $oldname]);

        $response = $this->putJson('api/warehouse/' . $warehouse->id + 1 );
        $response->assertStatus(404) ;

        $response = $this->putJson('api/warehouse/' . $warehouse->id);
        $response->assertStatus(422);

        $response = $this->putJson('api/warehouse/' . $warehouse->id , ['name' => $newname]);
        $response->assertStatus(200);
        $response->assertJsonPath('data.name' , $newname);

        $this->assertDatabaseMissing('warehouses' , [
            'name' => $oldname
        ]) ;

        $this->assertDatabaseHas('warehouses' , [
            'id' => $warehouse->id ,
            'name' => $newname
        ]) ;
    }

    public function test_destroy_WareHouse()
    {
        WareHouse::truncate();
        $name = "saad" ;
        $warehouse = WareHouse::factory()->create(['name' => $name]);
        $id = $warehouse->id ;

        $response = $this->deleteJson('api/warehouse/' . 100000) ;
        $response->assertStatus(404) ;

        $response = $this->deleteJson('api/warehouse/' . $id) ;
        $response->assertStatus(204) ;
        $this->assertDatabaseMissing('warehouses' , [
            'id' => $id ,
        ]);
        $this->assertDatabaseMissing('warehouses' , [
            'name' => $name ,
        ]);
    }
}
