<?php

namespace Tests\Feature;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\CompanyResource;
use App\Models\Category;
use App\Models\Company;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use SebastianBergmann\Type\VoidType;
use Tests\TestCase;

class MedicinesCrudTest extends TestCase
{
    use RefreshDatabase ;

    protected $category ;
    protected $company ;

    public function setUp():void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->company = Company::factory()->create();
    }


    public function test_medicine_index_return_paginated_recourds_currectly()
    {
        Medicine::truncate();

        Medicine::factory(16)->create([
            'company_id' => $this->company->id ,
            'category_id' => $this->category->id ,
        ]) ;

        $response = $this->get('api/medicine');

        $response->assertStatus(200);
        $response->assertJsonCount(15 , 'data');
        $response->assertJsonPath('meta.last_page' , 2) ;
    }

    
    public function test_store_new_medicine()
    {
        Medicine::truncate();

        $sname = "saad" ;
        $cname = "saad" ;
        $mx_amount = 999 ;
        $price = 333 ;
    
        $response = $this->postJson('api/medicine' , [
            'scientific_name' => $sname , 
            'commercial_name' => $cname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('medicines', [
            'scientific_name' => $sname , 
            'commercial_name' => $cname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 
            'total_amount' => null ,

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id ,  
        ]);

        $this->assertDatabaseCount('medicines' , 1 ) ;
    }
    
    public function test_show_medicine()
    {
        Medicine::truncate();
        
        $sname = "saad" ;
        $cname = "saad" ;
        $mx_amount = 999 ;
        $price = 333 ;

        $dose_not_exist_id = 1 ;
        $response = $this->getJson('api/medicine/'.$dose_not_exist_id);
        $response->assertStatus(404) ;
        

        $medicine = Medicine::factory()->create([
            'scientific_name' => $sname , 
            'commercial_name' => $cname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);


        $response = $this->get('api/medicine/'. $medicine->id + 14888888); 
        $response->assertStatus(404);

        $response = $this->getJson('api/medicine/'. $medicine->id ); 

        
        $response->assertStatus(200);

        $response->assertJsonFragment([
            'data'=> [
                'id' =>(string)$medicine->id,
                'scientific_name' => $sname ,
                'commercial_name' => $cname ,
                'max_amount' => $mx_amount ,
                'total_amount' =>  $medicine->total_amount,
                'price' => $price ,

                'category' => new CategoryResource($this->category) , 
                'company' => new CompanyResource($this->company) ,

            ],
        ]);

    }

    public function test_store_the_same_commercial_and_scientific_name_with_the_same_company_id()
    {
        Medicine::truncate();

        $sname = "saad" ;
        $cname = "saad" ;
        $mx_amount = 999 ;
        $price = 333 ;
    
        $response = $this->postJson('api/medicine' , [
            'scientific_name' => $sname , 
            'commercial_name' => $cname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);

        $response->assertStatus(201);

        $this->assertDatabaseHas('medicines', [
            'scientific_name' => $sname , 
            'commercial_name' => $cname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 
            'total_amount' => null ,

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id ,  
        ]);

        $this->assertDatabaseCount('medicines' , 1 ) ;

        $response = $this->postJson('api/medicine' , [
            'scientific_name' => $sname , 
            'commercial_name' => $cname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);

        $response->assertStatus(422);
        $this->assertDatabaseCount('medicines' , 1 ) ;
    }
    
    public function test_update_names_medicine()
    {
        Medicine::truncate();

        $oldsname = "saad" ;
        $newsname ="newsaad" ;

        $oldcname = "saad" ;
        $newcname = "newsaad" ;

        $mx_amount = 999 ;
        $price = 333 ;

        $dose_not_exist_id = 1 ;
        $response = $this->putJson('api/medicine/'.$dose_not_exist_id , [
            'scientific_name' => $newsname ,
            'commercial_name' => $newcname ,
            'max_amount' => $mx_amount ,
            'price' => $price ,

            'company_id' => $this->company->id ,
            'category_id' => $this->category->id ,
        ]);
        $response->assertStatus(404) ;

        $medicine = Medicine::factory()->create([
            'scientific_name' => $oldsname , 
            'commercial_name' => $oldcname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);

        $response = $this->putJson('api/medicine/'.$medicine->id , [
            'scientific_name' => $newsname ,
            'commercial_name' => $newcname ,
            'max_amount' => $mx_amount ,
            'price' => $price ,

            'company_id' => $this->company->id ,
            'category_id' => $this->category->id ,
        ]);

        $response->assertStatus(200) ;

        $this->assertDatabaseMissing('medicines' , [
            'scientific_name' => $oldsname ,
            'commercial_name' => $oldcname ,
        ]);

        $this->assertDatabaseHas('medicines' , [
            'scientific_name' => $newsname ,
            'commercial_name' => $newcname ,
        ]);
    }

    public function test_update_the_same_commercial_and_scientific_name_with_the_same_company_id()
    {
        Medicine::truncate();

        $sname = "saad" ;

        $cname1 = "saad1" ;
        $cname2 = "saad2" ;

        $mx_amount = 999 ;
        $price = 333 ;

        $medicine1 = Medicine::factory()->create([
            'scientific_name' => $sname , 
            'commercial_name' => $cname1 , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);
        $medicine2 = Medicine::factory()->create([
            'scientific_name' => $sname , 
            'commercial_name' => $cname2 , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);

        $response = $this->putJson('api/medicine/'.$medicine1->id , [
            'scientific_name' => $sname ,
            'commercial_name' => $cname2 ,
            'max_amount' => $mx_amount ,
            'price' => $price ,

            'company_id' => $this->company->id ,
            'category_id' => $this->category->id ,
        ]);

        $response->assertStatus(422) ;
    }

    public function test_destroy_medicine()
    {
        Medicine::truncate();

        $sname = "saad" ;
        $cname = "saad" ;
        $mx_amount = 999 ;
        $price = 333 ;
        

        $medicine = Medicine::factory()->create([
            'scientific_name' => $sname , 
            'commercial_name' => $cname , 
            'max_amount' => $mx_amount , 
            'price' => $price , 

            'company_id' => $this->company->id , 
            'category_id' => $this->category->id , 
        ]);

        $response = $this->deleteJson('api/medicine/' . 10000000) ;
        $response->assertStatus(404) ;

        $response = $this->deleteJson('api/medicine/' . $medicine->id) ;
        $response->assertStatus(204) ;
        $this->assertDatabaseMissing('medicines' , [
            'id' => $medicine->id ,
        ]);
    }
}
