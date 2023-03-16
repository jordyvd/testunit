<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\traits\RefreshCacheTest;

class ClientsTest extends TestCase
{
    use RefreshCacheTest;
    
    use RefreshDatabase;
  
    /** @test */
    public function storeClients()
    {
        $this->WithoutExceptionHandling();
                
        $params = [
            'name' => 'jack',
            'email' => 'jack@gmail.com',
            'phone' => '66799999',  
        ]; 

        $count = DB::selectOne('select count(*) `count` from clients')->count;

        $response = $this->post('/api/clients', $params);

        $response->assertStatus(200);

        $this->assertDatabaseCount('clients', $count + 1);
        
        // $this->assertDatabaseHas('clients', $params);
        $client_last = DB::selectOne('select name, email, phone from clients order by id desc limit 1');

        $this->assertEquals($client_last->name, $params['name']);

        $this->assertEquals($client_last->email, $params['email']);

        $this->assertEquals($client_last->phone, $params['phone']);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function getClients()
    {
        $this->WithoutExceptionHandling();

        $params = [
            'name' => 'jack',
            'email' => 'jack@gmail.com',
            'phone' => '66799999',  
        ]; 

        $this->post('/api/clients', $params);

        $response = $this->get('/api/clients');  

        $response->assertStatus(200);

        $this->assertNotEmpty($response);

        $this->assertArrayHasKey(0, $response);

        $response->assertJsonFragment($params);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function updateClint()
    {
        $this->WithoutExceptionHandling();

        $params = [
            'name' => 'jack',
            'email' => 'jack@gmail.com',
            'phone' => '66799999',  
        ];         

        $this->post('/api/clients', $params);

        $params_update = [
            'name' => 'jack2222',
            'email' => 'jack@gmail.com',
            'phone' => '66799999',  
        ];         

        $response = $this->put('/api/clients/1', $params_update);

        $response->assertStatus(200);

        $this->assertDatabaseHas('clients', $params_update);        
    }
}
