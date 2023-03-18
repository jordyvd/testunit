<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use App\traits\RefreshCacheTest;

class Clients2Test extends TestCase
{
    //use RefreshDatabase; 

    use RefreshCacheTest;

   /** @test */
   public function store2Client()
   {
        $this->WithoutExceptionHandling();

        $count = DB::selectOne('select count(*) `count` from clients')->count;
   
        $params = [
            "name" => "rihana",
            "email" => "rihana@gmail.com",
            "phone" => "123456"
        ];

        $reponse = $this->post('/api/clients', $params);

        $reponse->assertStatus(200);

        $this->assertDatabaseCount('clients', $count + 1);

        // $this->assertDatabaseHas('clients', $params);
        $last_client = DB::selectOne('select name, email, phone from clients order by id desc limit 1');

        $this->assertEquals($last_client->name, $params['name']);

        $this->assertEquals($last_client->email, $params['email']);

        $this->assertEquals($last_client->phone, $params['phone']);

        $this->RefreshCacheTest();
   }    

   /** @test */
   public function getClients2()
   {
        $this->WithoutExceptionHandling();    

        $params = [
            "name" => "rihana",
            "email" => "rihana@gmail.com",
            "phone" => "123456"
        ];

        $this->post('/api/clients', $params);

        $reponse = $this->get('/api/clients');

        $reponse->assertStatus(200);

        $this->assertArrayHasKey(0, $reponse);

        $reponse->assertJsonFragment($params);

        $this->RefreshCacheTest();
   }

   /** @test */
   public function updateClient2()
   {
        $this->WithoutExceptionHandling();    

        $params = [
            "name" => "rihana",
            "email" => "rihana@gmail.com",
            "phone" => "123456"
        ];

        $this->post('/api/clients', $params);
        
        $last_client = DB::selectOne('select id, name, email, phone from clients order by id desc limit 1');

        $params_update = [
            "name" => "rihana2",
            "email" => "rihana2@gmail.com",
            "phone" => "123456"
        ];

        $reponse = $this->put('/api/clients/'. $last_client->id, $params_update);

        $reponse->assertStatus(200);

        $this->assertNotEquals($last_client->name, $params_update['name']); 
        
        $this->assertNotEquals($last_client->email, $params_update['email']); 

        $this->RefreshCacheTest();
    }

    /** @test */
    public function deleteClient2()
    {
        $this->WithoutExceptionHandling();    

        $params = [
            "name" => "rihana",
            "email" => "rihana@gmail.com",
            "phone" => "123456"
        ];

        $this->post('/api/clients', $params);

        $last_client = DB::selectOne('select id, name, email, phone from clients order by id desc limit 1');

        $reponse = $this->delete('/api/clients/'. $last_client->id);

        $reponse->assertStatus(200);

        $client = DB::select('select id from clients where id = ?', [$last_client->id]);

        $this->assertCount(0, $client);
    }

    /** @test */
    public function validateFieldStoreClient()
    {
        $count = DB::selectOne('select count(*) `count` from clients')->count;
   
        $params = [
            "name" => '',
            "email" => "rihanagmail.com",
            "phone" => "123456"
        ];

        $reponse = $this->post('/api/clients', $params);

        $reponse->assertStatus(302);        

        $reponse->assertSessionHasErrors(['email']);
    }
    /** @test */
    public function storeCards()
    {
        $this->WithoutExceptionHandling();   
        $params = [
            "number" => "18998899",
        ];

        $reponse = $this->post('/api/cards', $params);

        $reponse->assertStatus(200);
    } 
}
