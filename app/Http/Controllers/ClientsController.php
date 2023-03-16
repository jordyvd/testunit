<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClientsController extends Controller
{
    public function store(Request $request)
    {
        $procedure = "insert into clients(name, email, phone) values(?,?,?)";
        $params = [
            $request->name,
            $request->email,
            $request->phone,
        ];
        DB::statement($procedure, $params);
    }

    public function index()
    {
        $procedure = "select id, name, email, phone from clients";
        $data = DB::select($procedure);
        return $data;
    }

    public function update(Request $request, $id)
    {
        $procedure = "update clients set name = ?, email = ?, phone = ? where id = ?";
        $params = [
            $request->name,
            $request->email,
            $request->phone,
            $id
        ];
        DB::statement($procedure, $params);
    }
}
