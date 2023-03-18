<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\traits\RefreshCacheTest;
use Illuminate\Support\Facades\DB;

class Clients5Test extends TestCase
{
    use RefreshDatabase;

    use RefreshCacheTest;  

    /** @test */
    public function store5Client()
    {
        $count_clients = DB::selectOne('select count(*) `count` from clients')->count;

        $params = [
            "name" => "marlon",
            "email" => "marlon@gmail.com",
            "phone" => "99888"
        ];

        $response = $this->post('/api/clients', $params);

        $response->assertStatus(200);

        $this->assertDatabaseCount('clients', $count_clients + 1);

        // $this->assertDatabaseHas('clients', $params);
        $last_client = DB::selectOne("select name, email, phone from clients order by id desc limit 1");

        $this->assertEquals($last_client->name, $params['name']);

        $this->assertEquals($last_client->email, $params['email']);

        $this->assertEquals($last_client->phone, $params['phone']);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function get5Clients()
    {
        $params = [
            "name" => "marlon",
            "email" => "marlon@gmail.com",
            "phone" => "99888"
        ];

        $this->post('/api/clients', $params);

        $response = $this->get('/api/clients');

        $response->assertStatus(200);

        $this->assertArrayHasKey(0, $response);

        $response->assertJsonFragment($params);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function update5CLient()
    {
        $params = [
            "name" => "marlon",
            "email" => "marlon@gmail.com",
            "phone" => "99888"
        ];

        $this->post('/api/clients', $params);

        $last_client = DB::selectOne("select id, name, email, phone from clients order by id desc limit 1");

        $params_update = [
            "name" => "marlon2",
            "email" => "marlon2@gmail.com",
            "phone" => "99888"
        ];

        $response = $this->put('/api/clients/'. $last_client->id, $params_update);

        $client = DB::selectOne("select id, name, email, phone from clients where id = ?", [$last_client->id]);

        $response->assertStatus(200);

        $this->assertNotEquals($client->name, $last_client->name);

        $this->assertEquals($client->name, $params_update['name']);

        $this->assertEquals($client->phone, $params_update['phone']);

        $this->RefreshCacheTest();
    }

    /** @test */
    public function delete5Client()
    {
        $params = [
            "name" => "marlon",
            "email" => "marlon@gmail.com",
            "phone" => "99888"
        ];

        $this->post('/api/clients', $params);

        $last_client_id = DB::selectOne("select id, name, email, phone from clients order by id desc limit 1")->id;

        $response = $this->delete('/api/clients/'.$last_client_id);
        
        $clients = DB::select('select id from clients where id = ?', [$last_client_id]);

        $this->assertCount(0, $clients);

        $response->assertStatus(200);
    }
}
