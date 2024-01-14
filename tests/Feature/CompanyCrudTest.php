<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompanyCrudTest extends TestCase
{
    use RefreshDatabase ;

    public function test_company_index_return_paginated_recourds_currectly()
    {
        Company::truncate();

        Company::factory(16)->create() ;

        $response = $this->get('api/company');

        $response->assertStatus(200);
        $response->assertJsonCount(15 , 'data');
        $response->assertJsonPath('meta.last_page' , 2) ;
    }

    public function test_store_company()
    {
        Company::truncate();

        $name = "saad" ;

        $response = $this->postJson('api/company' , ['name' => $name]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('companies', ['name' => $name ]);
    }

    public function test_show_company()
    {
        Company::truncate();
        $name = "saad" ;

        $dose_not_exist_id = 1 ;
        $response = $this->getJson('api/company/'.$dose_not_exist_id);
        $response->assertStatus(404) ;

        $company = Company::factory()->create(['name' => $name]);

        $response = $this->getJson('api/company/ '.$company->id ); 

        
        $response->assertStatus(200);
        $response->assertJsonPath('data.name' , $name);
    }

    public function test_update_company()
    {
        Company::truncate();

        $oldname = "hela" ;
        $newname = "saad" ;

        $company = Company::factory()->create(['name' => $oldname]);

        $response = $this->putJson('api/company/' . $company->id + 1 );
        $response->assertStatus(404) ;

        $response = $this->putJson('api/company/' . $company->id);
        $response->assertStatus(422);

        $response = $this->putJson('api/company/' . $company->id , ['name' => $newname]);
        $response->assertStatus(200);
        $response->assertJsonPath('data.name' , $newname);

        $this->assertDatabaseMissing('companies' , [
            'name' => $oldname
        ]) ;

        $this->assertDatabaseHas('companies' , [
            'id' => $company->id ,
            'name' => $newname
        ]) ;
    }

    public function test_destroy_company()
    {
        Company::truncate();
        $name = "saad" ;
        $company = Company::factory()->create(['name' => $name]);
        $id = $company->id ;

        $response = $this->deleteJson('api/company/' . 100000) ;
        $response->assertStatus(404) ;

        $response = $this->deleteJson('api/company/' . $id) ;
        $response->assertStatus(204) ;
        $this->assertDatabaseMissing('companies' , [
            'id' => $id ,
        ]);
        $this->assertDatabaseMissing('companies' , [
            'name' => $name ,
        ]);
    }

    public function test_relationship_company_paginate_HasMany_medicines()
    {
        Company::truncate();
        Medicine::truncate();

        $company1 = Company::factory()->create() ;
        $company2 = Company::factory()->create() ;
        $category = Category::factory()->create() ;
        $medicines1 = Medicine::factory(2)->create([
            'company_id' => $company1->id ,
            'category_id' => $category->id
        ]) ;
        $medicines2 = Medicine::factory(2)->create([
            'company_id' => $company2->id ,
            'category_id' => $category->id
        ]) ;

        $response = $this->getJson('api/company/' .$company1->id . '/medicines');

        $response->assertStatus(200);
        
        $data = $response->json();

        collect($data['data'])->each(function ($item) use ($company1) {
            $this->assertEquals($company1->id , $item['company']['id']);
        });
    }
    
}
