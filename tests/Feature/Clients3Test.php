<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\traits\RefreshCacheTest;
use Illuminate\Support\Facades\DB;

class Clients3Test extends TestCase
{
    use RefreshDatabase;

    use RefreshCacheTest;

    /** @test */
    public function store3Client()
    {
        $this->WithoutExceptionHandling();

        $countClients = DB::selectOne('select count(*) `count` from clients')->count;

        $params = [
            "name" => 'lola',
            "email" => 'lola@gmail.com',
            "phone" => '985620269'
        ];    

        $response = $this->post('/api/clients', $params);

        $response->assertStatus(200);

        $this->assertDatabaseCount('clients', $countClients + 1);

        $last_client = DB::selectOne('select id, name, email, phone from clients order by id desc limit 1');

        // $this->assertDatabaseHas('clients', $params);

        $this->assertEquals($last_client->name, $params['name']);

        $this->assertEquals($last_client->email, $params['email']);

        $this->assertEquals($last_client->phone, $params['phone']);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function get3Clients()
    {
        $this->WithoutExceptionHandling();

        $params = [
            "name" => 'lola',
            "email" => 'lola@gmail.com',
            "phone" => '985620269'
        ];    

        $this->post('/api/clients', $params);
        // ----- TEST
        $response = $this->get('/api/clients');

        $response->assertStatus(200);

        $this->assertArrayHasKey(0, $response);

        $response->assertJsonFragment($params);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function update3Clients()
    {
        $this->WithoutExceptionHandling();

        $params = [
            "name" => 'lola',
            "email" => 'lola@gmail.com',
            "phone" => '985620269'
        ];    

        $this->post('/api/clients', $params);
        // ----- TEST
        $params_update = [
            "name" => 'lola2',
            "email" => 'lola2@gmail.com',
            "phone" => '985620269'
        ];   

        $last_client = DB::selectOne('select id, name, email, phone from clients order by id desc limit 1');

        $response = $this->put('/api/clients/'.$last_client->id, $params_update);

        $response->assertStatus(200);

        $this->assertNotEquals($last_client->name, $params_update['name']);

        $this->assertNotEquals($last_client->email, $params_update['email']);

        $this->assertEquals($last_client->phone, $params_update['phone']);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function delete3Client()
    {
        $this->WithoutExceptionHandling();

        $params = [
            "name" => 'lola',
            "email" => 'lola@gmail.com',
            "phone" => '985620269'
        ];    

        $this->post('/api/clients', $params);
        // ----- TEST
        $last_client_id = DB::selectOne('select id from clients order by id desc limit 1')->id;

        $response = $this->delete('/api/clients/'. $last_client_id);

        $response->assertStatus(200);    
        
        // $this->assertDatabaseCount('clients', 0);
        $client = DB::select('select * from clients where id = ?', [$last_client_id]);

        $this->assertCount(0, $client);
    }

    /** @test */
    // public function 
}
