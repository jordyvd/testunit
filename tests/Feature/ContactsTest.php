<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;

class ContactsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function storeContact()
    {
        $count_contacts = DB::selectOne('select count(*) `count` from contacts')->count;

        $this->WithoutExceptionHandling(); 
        
        $params = [
            "name" => 'leonardo',
            "email" => 'leoncio@gmail.com',
            "phone" => '985660242'
        ];

        $response = $this->post('/api/contacts', $params);


        $response->assertStatus(200);

        $this->assertDatabaseCount('contacts', $count_contacts + 1);
 
        $last_contact = DB::selectOne('select name, email, phone from contacts order by id desc limit 1');
        
        $this->assertEquals($last_contact->name, $params['name']);
        $this->assertEquals($last_contact->email, $params['email']);
        $this->assertEquals($last_contact->phone, $params['phone']);
        // $this->assertDatabaseHas('contacts', $params);
    }

    /** @test */
    public function getContacts()
    {
        $this->WithoutExceptionHandling(); 

        $params = [
            "name" => 'leonardo',
            "email" => 'leoncio@gmail.com',
            "phone" => '985660242'
        ];

        $this->post('/api/contacts', $params); 
        
        // TEST
        $response = $this->get('/api/contacts');

        $response->assertStatus(200);

        $this->assertArrayHasKey(0, $response);

        $response->assertJsonFragment($params);
    }

    /** @test */
    public function updateContact()
    {
        $this->WithoutExceptionHandling(); 

        $params = [
            "name" => 'leonardo',
            "email" => 'leoncio@gmail.com',
            "phone" => '985660242'
        ];

        $this->post('/api/contacts', $params); 
        
        // TEST
        $params_update = [
            "name" => 'leonardo2',
            "email" => 'leoncio2@gmail.com',
            "phone" => '9856602422' //no me llamen
        ];

        $last_contact = DB::selectOne('select id, name, email, phone from contacts order by id desc limit 1');
        
        $response = $this->put('api/contacts/'. $last_contact->id, $params_update);

        $response->assertStatus(200);

        $contact = DB::selectOne('select name, email, phone from contacts order by id desc limit 1');

        $this->assertNotEquals($last_contact->name, $contact->name);
        $this->assertNotEquals($last_contact->email, $contact->email);
        $this->assertNotEquals($last_contact->phone, $contact->phone);

        $this->assertEquals($contact->phone, $params_update['phone']);
    }

    /** @test */
    public function deleteContact()
    {
        $this->WithoutExceptionHandling(); 

        $params = [
            "name" => 'leonardo',
            "email" => 'leoncio@gmail.com',
            "phone" => '985660242'
        ];

        $this->post('/api/contacts', $params); 
        // TEST
        $last_contact = DB::selectOne('select id, name, email, phone from contacts order by id desc limit 1');

        $response = $this->delete('/api/contacts/'.$last_contact->id);

        $response->assertStatus(200);

        $contacts = DB::select('select id from contacts where id = ?', [$last_contact->id]);

        $this->assertCount(0, $contacts);
    }

    /** @test */
    public function validateFieldsStore()
    {
        $params = [
            "name" => '',
            "email" => 'leonciogmail.com',
            "phone" => '999'
        ];

        $response = $this->post('/api/contacts', $params);     
        
        $response->assertStatus(302);

        $response->assertSessionHasErrors(['name', 'email', 'phone']);
    }
}
