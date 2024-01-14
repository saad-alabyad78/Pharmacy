<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryCrudTest extends TestCase
{
    use RefreshDatabase ;

    public function test_Category_index_return_paginated_recourds_currectly()
    {
        Category::truncate();

        Category::factory(16)->create() ;

        $response = $this->get('api/category');

        $response->assertStatus(200);
        $response->assertJsonCount(15 , 'data');
        $response->assertJsonPath('meta.last_page' , 2) ;
    }

    public function test_store_Category()
    {
        Category::truncate();

        $name = "saad" ;

        $response = $this->postJson('api/category' , ['name' => $name]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('categories', ['name' => $name ]);
    }

    public function test_show_Category()
    {
        Category::truncate();
        $name = "saad" ;

        $dose_not_exist_id = 1 ;
        $response = $this->getJson('api/category/'.$dose_not_exist_id);
        $response->assertStatus(404) ;

        $category = Category::factory()->create(['name' => $name]);

        $response = $this->getJson('api/category/'.$category->id ); 

        
        $response->assertStatus(200);
        $response->assertJsonPath('data.name' , $name);
    }

    public function test_update_Category()
    {
        Category::truncate();

        $oldname = "hela" ;
        $newname = "saad" ;

        $category = Category::factory()->create(['name' => $oldname]);

        $response = $this->putJson('api/category/' . $category->id + 1 );
        $response->assertStatus(404) ;

        $response = $this->putJson('api/category/' . $category->id);
        $response->assertStatus(422);

        $response = $this->putJson('api/category/' . $category->id , ['name' => $newname]);
        $response->assertStatus(200);
        $response->assertJsonPath('data.name' , $newname);

        $this->assertDatabaseMissing('categories' , [
            'name' => $oldname
        ]) ;

        $this->assertDatabaseHas('categories' , [
            'id' => $category->id ,
            'name' => $newname
        ]) ;
    }

    public function test_destroy_Category()
    {
        Category::truncate();
        $name = "saad" ;
        $category = Category::factory()->create(['name' => $name]);
        $id = $category->id ;

        $response = $this->deleteJson('api/category/' . 100000) ;
        $response->assertStatus(404) ;

        $response = $this->deleteJson('api/category/' . $id) ;
        $response->assertStatus(204) ;
        $this->assertDatabaseMissing('categories' , [
            'id' => $id ,
        ]);
        $this->assertDatabaseMissing('categories' , [
            'name' => $name ,
        ]);
    }

    public function test_relationship_Category_paginate_HasMany_medicines()
    {
        Category::truncate();
        Medicine::truncate();

        $category1 = Category::factory()->create() ;
        $category2 = Category::factory()->create() ;
        $company = Company::factory()->create() ;

        $medicines1 = Medicine::factory(2)->create([
            'category_id' => $category1->id ,
            'company_id' => $company->id
        ]) ;
        $medicines2 = Medicine::factory(2)->create([
            'category_id' => $category2->id ,
            'company_id' => $company->id
        ]) ;

        $response = $this->getJson('api/category/' .$category1->id . '/medicines');

        $response->assertStatus(200);
        
        $data = $response->json();

        collect($data['data'])->each(function ($item) use ($category1) {
            $this->assertEquals($category1->id , $item['category']['id']);
        });
    }
    
}
