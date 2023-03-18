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
    public function validateRequiredStoreClients()
    {
        // $this->WithoutExceptionHandling();

        $params = [
            'name' => '',
            'email' => 'jackgmail.com',
            'phone' => '66799999',  
        ]; 

        $response = $this->post('/api/clients', $params);

        $response->assertStatus(302);
        
        $response->assertSessionHasErrors(['name', 'email']);

        $this->RefreshCacheTest();
    }


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
    public function updateClient()
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

        $last_client = DB::selectOne('select id, name, email from clients order by id desc limit 1');

        $response = $this->put('/api/clients/'.$last_client->id, $params_update);

        $response->assertStatus(200);

        $this->assertNotEquals($last_client->name, $params_update['name']);        

        $this->assertEquals($last_client->email, $params_update['email']);    

        $this->RefreshCacheTest();    
    }

    /** @test */
    public function deleteClient()
    {   
        $this->WithoutExceptionHandling();

        $params = [
            'name' => 'jack',
            'email' => 'jack@gmail.com',
            'phone' => '66799999',  
        ];         

        $this->post('/api/clients', $params);

        $last_client = DB::selectOne('select id, name from clients order by id desc limit 1');

        $response = $this->delete('/api/clients/'. $last_client->id);

        $response->assertStatus(200);

        $client = DB::select('select id, name from clients where id = ? order by id desc limit 1', [$last_client->id]);

        $this->assertCount(0, $client); 

        $this->RefreshCacheTest();
    }
}
