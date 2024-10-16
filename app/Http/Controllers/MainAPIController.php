<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\UserMeta;


class MainAPIController extends Controller
{
    public function GetUsers(Request $request){
        $users = DB::table('users')->where('role','!=','0')->get();
        return response()->json(['users' => $users,200]);
    }

    public function CreateUser(Request $request){
        $name = $request['name'];
        $email = $request['email'];
        $role = $request['role'];
        $profileImage = $request['profileImage'];
        $password = $request['password'];
        $createdBy = $request['createdBy'];
    
        if($name == "" || $email == "" || $role == "" || $profileImage == ""){
            return response()->json(["Message"=>"Cannot Leave Feild Blank "], 500);
        }
    
        $HashPassword = Hash::make($password, [
            'rounds' => 12,
        ]);
    

        $USERCOUNT =  User::where('email','=',$email)->count();
        if($USERCOUNT > 0) {
            return response()->json(["Message"=>"Email Found Please Use Different Email"], 500);
        }

        $UserID = User::create([
            "name" => $name,
            "email" => $email,
            "password" => $HashPassword,
        ]);
        
        $userMeta = UserMeta::create([
            "userId" => $UserID->id,
            "featuredImage" => $profileImage,
            "role" => $role,
            "createBy" => $createdBy,
        ]);
        $data = [
            "Message" => "User Created",
            "UserID" => $userMeta->id,
        ];
        return response()->json($data, 200);
    }

   
}
