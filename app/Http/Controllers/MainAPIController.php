<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserMeta;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MainAPIController extends Controller
{
    public function GetUsers(Request $request)
    {
        $users = DB::table('users')->where('role', '!=', '0')->get();
        return response()->json(['users' => $users, 200]);
    }

    public function CreateUser(Request $request)
    {
        $name = $request['name'];
        $email = $request['email'];
        $role = $request['role'];
        $profileImage = $request['profileImage'];
        $password = $request['password'];
        $createdBy = $request['createdBy'];

        if ($name == "" || $email == "" || $role == "" || $profileImage == "") {
            return response()->json(["Message" => "Cannot Leave Feild Blank "], 500);
        }

        $HashPassword = Hash::make($password, [
            'rounds' => 12,
        ]);

        $USERCOUNT = User::where('email', '=', $email)->count();
        if ($USERCOUNT > 0) {
            return response()->json(["Message" => "Email Found Please Use Different Email"], 500);
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

    public function validate(Request $request)
    {
        $device_id = $request['device_id'];
        $device_key = $request['device_key'];

        if ($device_id == "" || $device_key == "") {
            $data = [
                "Message" => "Device ID Or Device Key Is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $Area = Area::where('Orin_Device_ID',$device_id)->where('Orin_Device_Key',$device_key)->select('id','Area_Name')->first();
        if($Area){
            $data = [
                "Message" => "Device connected and validated",
                "Status" => "Success",
                "Device_id" => $device_id,
                "area_id" => $Area->id,
                "Area_Name" => $Area->Area_Name
            ];
            return response()->json($data, 200);
        }
        else{
            $data = [
                "Message" => "Device key mismatch or device not registered",
                "status" => "unfound",
            ];
            return response()->json($data, 404);
        }
    }   


    public function livefeed(Request $request,$area_id){
        if($area_id == ""){
            $data = [
                "Message" => "Area ID is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $Area = Area::where('id',$area_id)->first();
        if($Area){
            $data = [
                "area_id" => $Area,
                "live_feed_url" => "https://livefeed.example.com/DEVICE123",
                "status" => 'success',
            ];
            return response()->json($data, 200);
        }
        else{
            $data = [
                "status" => 'unfound',
                "message" => "url not found",
            ];
            return response()->json($data, 404);
        }
    }
}
