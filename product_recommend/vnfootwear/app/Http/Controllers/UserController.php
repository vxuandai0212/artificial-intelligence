<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Arr;

class UserController extends Controller
{
    public function getMany(Request $request)
    {
        $limit = $request->limit;
        $offset = $request->offset;
        $name = $request->name;
        $username = $request->username;  
        $email = $request->email;  
        $role = $request->role;  

        $matches = array();
        if ($name) {
            $matches["name"] = $name;
        }
        if ($username) {
            $matches["username"] = $username;
        }
        if ($email) {
            $matches["email"] = $email;
        }
        if ($role) {
            $matches["role"] = $role;
        }

        $users = User::where($matches)
        ->customPaginate($limit, $offset)->get();
    

        $total = User::where($matches)
        ->count();

        return [$users, $total];
    }

    public function get($id)
    {
        $user = User::find($id);
        return $user;
    }

    public function add(Request $request)
    {
        $user = new User;

        $user->name = $request->name;
        $user->username = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->password = Hash::make($request->password);

        $user->save();

        return response()->json($user, 201);
    }

    public function update($id, Request $request)
    {
        $user = User::find($id);
        $user->name = $request->user['name'];
        $user->username = $request->user['username'];
        $user->email = $request->user['email'];
        $user->role = $request->user['role'];
    
        $user->save();

        return response()->json($user, 201);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return response()->json(null, 204);
    }

    public function test()
    {
        $path = "/home/lam/Desktop/vini-intern/product_recommend/web/vnfootwear/database/seeds/data/products_sample_data.csv";
        $csv = array();
        $lines = file($path, FILE_IGNORE_NEW_LINES);

        foreach ($lines as $key => $value)
        {
            $csv[$key] = str_getcsv($value);
        }

        // remove label
        array_shift($csv);
        // remove product loss props
        $filtered = Arr::where($csv, function ($value, $key) {
            return count($value) > 2;
        });

        echo '<pre>';
        print_r($filtered);
        echo '</pre>';

        // return view('test');
    }
}
