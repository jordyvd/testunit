<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ContactRequest;

class ContactsController extends Controller
{
    public function index()
    {
        return DB::select('select name, email, phone from contacts');  
    }

    public function store(ContactRequest $request)
    {
        $statement = "insert into contacts(name, email, phone) values(?,?,?)";
        $params = [
            $request->name,
            $request->email,
            $request->phone,
        ];
        DB::statement($statement, $params);
    }

    public function update(Request $request, $id)
    {
        $statement = "update contacts set name = ?, email = ?, phone = ? where id = ?";
        $params = [
            $request->name, 
            $request->email,
            $request->phone,  
            $id
        ]; 
        DB::statement($statement, $params);
    }

    public function delete($id)
    {
        DB::statement('delete from contacts where id = ?', [$id]);
    }
}
