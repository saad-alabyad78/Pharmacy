<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Company;
use App\Models\Medicine;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MedicineSearchTest extends TestCase
{
    use RefreshDatabase;

    protected $category ;
    protected $company ;

    protected $med1 , $med2 ;

    protected $cname , $sname , $_cname , $_sname ;

    public function setUp():void
    {
        parent::setUp();

        $this->category = Category::factory()->create();
        $this->company = Company::factory()->create();

        $this->cname = "c cc" ;
        $this->sname = "s ss" ;

        $this->med1 = Medicine::factory()->create([
            'scientific_name' => $this->sname,
            'commercial_name' => $this->cname,
            'company_id' => $this->company->id ,
            'category_id' => $this->category->id ,
        ]);

        $this->_cname = "__cc" ;
        $this->_sname = "__ss" ;

        $this->med2 = Medicine::factory()->create([
            'scientific_name' => $this->_sname,
            'commercial_name' => $this->_cname,
            'company_id' => $this->company->id ,
            'category_id' => $this->category->id ,
        ]);
    }

    public function test_empty_search()
    {
        $space = ' ' ;
        $response = $this->get('api/medicine/search/'.$space);

        $response->assertStatus(200);
        $response->assertJson([]);
    }
    public function test_full_scientific_name_search()
    {   
        $response = $this->get('api/medicine/search/'.$this->sname);

        $response->assertStatus(200);
        $response->assertJsonCount( 1 , 'data');
        $response->assertJsonPath('data.0.scientific_name', $this->sname );
    }

    public function test_full_commercial_name_search()
    {
        $response = $this->get('api/medicine/search/'.$this->cname);

        $response->assertStatus(200);
        $response->assertJsonCount( 1 , 'data');
        $response->assertJsonPath('data.0.commercial_name', $this->cname );
    }

    public function test_fragment_names_search()
    {
        $response = $this->get('api/medicine/search/'.$this->sname[0]);// s in ssss(med1) and __ss(med2)

        $response->assertStatus(200);
        $response->assertJsonCount(2, 'data');
    }

    public function test_underscore_fragment_in_the_names_search()
    {
        $response = $this->get('api/medicine/search/_');// _ in (__ss and __cc)(med2)

        // // //$response->assertJson(['id'=>987]);
        // $response->assertStatus(200);
        // $response->assertJsonCount(1, 'data');
        // $response->assertJsonPath('data.0.commercial_name', $this->_cname );
        // $response->assertJsonPath('data.0.scientific_name', $this->_sname );
    }
}
