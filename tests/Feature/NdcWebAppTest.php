<?php

namespace Tests\Feature;

use App\Region;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NdcWebAppTest extends TestCase
{
    //use RefreshDatabase;
    /** @test */
    public function a_region_can_be_added_to_ndc_app()
    {
        $this->withoutExceptionHandling();
        $response = $this->post('api/v1/regions', [
            'code' => '231Q',
            'description' => 'Western Region',
        ]);

        $response->assertOk();
        $this->assertCount(1, Region::all());
    }

    /** @test */
    public function a_code_is_required(){
        //$this->withoutExceptionHandling();
        $response = $this->post('api/v1/regions', [
            'code' => '',
            'description' => 'Western',
        ]);

        $response->assertSessionHasErrors('code');

    }

    /** @test */
    public function a_description_is_required(){
        //$this->withoutExceptionHandling();
        $response = $this->post('api/v1/regions', [
            'code' => '123Q',
            'description' => '',
        ]);

        $response->assertSessionHasErrors('description');

    }

    /** @test */
    public function a_region_can_be_update()
    {
        $this->withoutExceptionHandling();
        $this->post('api/v1/regions', [
            'code' => '231Q',
            'description' => 'Western Region',
        ]);

        $region = Region::first();

        $response = $this->patch('api/v1/regions/'.$region->id,[
            'code' => 'New Code',
            'description' => 'New Region',
            ]);

        $this->assertEquals('New Code', Region::first()->code);
        $this->assertEquals('New Region', Region::first()->description);

    }

    /** @test */
    public function a_region_can_be_deleted()
    {
        $this->withoutExceptionHandling();
        $this->post('api/v1/regions', [
            'code' => '231Q',
            'description' => 'Western Region',
        ]);

        $region = Region::first();

        $response = $this->delete('api/v1/regions/'.$region->id);

        $response->assertOk();
        $this->assertCount(0, Region::all());

    }
}
