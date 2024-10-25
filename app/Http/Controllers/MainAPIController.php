<?php

namespace App\Http\Controllers;

use App\Models\Alerts;
use App\Models\Area;
use App\Models\AssignCheckpoint;
use App\Models\Checkpoints;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Safety;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\WorkSite;
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
        $Area = Area::where('Orin_Device_ID', $device_id)->where('Orin_Device_Key', $device_key)->select('id', 'Area_Name')->first();
        if ($Area) {
            $data = [
                "Message" => "Device connected and validated",
                "Status" => "Success",
                "Device_id" => $device_id,
                "area_id" => $Area->id,
                "Area_Name" => $Area->Area_Name,
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Device key mismatch or device not registered",
                "status" => "unfound",
            ];
            return response()->json($data, 404);
        }
    }

    public function livefeed(Request $request, $area_id)
    {
        if ($area_id == "") {
            $data = [
                "Message" => "Area ID is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $Area = Area::where('id', $area_id)->first();
        if ($Area) {
            $data = [
                "area_id" => $Area,
                "live_feed_url" => "https://livefeed.example.com/DEVICE123",
                "status" => 'success',
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "status" => 'unfound',
                "message" => "url not found",
            ];
            return response()->json($data, 404);
        }
    }

    public function areaalerts(Request $request, $area_id)
    {
        if ($area_id == "") {
            $data = [
                "Message" => "Area ID is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $alert_code = $request['alert_code'];
        $risk_level = $request['risk_level'];
        $description = $request['description'];
        $captured_image_url = $request['captured_image_url'];
        if ($alert_code == "" || $risk_level == "" || $description == "" || $captured_image_url == "") {
            $data = [
                "Message" => "alert_code, risk_level, description, captured_image_url is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }

        $Area = Area::where('id', $area_id)->count();

        if ($Area === 0) {
            $data = [
                "Message" => "area not found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }

        $Alerts = Alerts::create([
            "alert_code" => $alert_code,
            "area_code" => $area_id,
            "risk_level" => $risk_level,
            "description" => $description,
            "captured_image_url" => $captured_image_url,
        ]);

        if ($Alerts) {
            $data = [
                "Message" => "Alert Created",
                "status" => "success",
            ];
            return response()->json($data, 200);
        }
    }

    public function worksitealerts(Request $request, $worksite)
    {

        $Area = Area::where('WSID', $worksite)
            ->join('alerts', 'alerts.area_code', '=', 'areas.id')
            ->select('alerts.*', 'areas.id as AID')
            ->get();

        if (count($Area) > 0) {
            $data = [
                "alerts" => $Area,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "area not found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function areadevice(Request $request, $area_id)
    {
        $Area = Area::where('id', $area_id)
            ->get();
        if (count($Area) > 0) {
            $data = [
                "alerts" => $Area,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "area not found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function worksiteMobile(Request $request, $email)
    {
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();

        if ($User) {
            $WorkSite = WorkSite::all();
            $data = [
                "data" => $WorkSite,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }

    }

    public function worksiteMobiledetails(Request $request, $id)
    {
        $WorkSite = WorkSite::where('id', $id)->first();
        if ($WorkSite == "") {
            $data = [
                "Message" => "Worksite not found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        } else {
            $Area = Area::where('WSID', $WorkSite->id)->get();

            $WorksiteDetail = [
                "Worksite" => $WorkSite,
                "Area" => $Area,
            ];

            $data = [
                "data" => $WorksiteDetail,
                "status" => "success",
            ];
            return response()->json($data, 200);
        }

    }

    public function allcommunication(Request $request)
    {
        $Notification = Notification::all();
        if ($Notification != "") {
            $data = [
                "data" => $Notification,
                "status" => "success",
            ];
            return response()->json($data, 200);
        }
    }

    public function safetyguideline(Request $request, $email)
    {
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();
        if ($User) {
            $Safety = Safety::all();
            $data = [
                "data" => $Safety,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function safetyguidelineDetails(Request $request, $id)
    {
        $Safety = Safety::where('id', $id)->first();
        if ($Safety == "") {
            $data = [
                "Message" => "Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }

        $AssignCheckpoint = AssignCheckpoint::where('SAFID', $Safety->id)->select('CHKID')->get();
        $SafetyArray = [
            "Safety" => $Safety,
        ];
        foreach ($AssignCheckpoint as $AC) {
            $Checkpoints = Checkpoints::where('id', $AC->CHKID)->first();
            array_push($SafetyArray, ["Checkpoints" => $Checkpoints]);
        }

        return response()->json($SafetyArray, 200);
    }

    public function checkoutMobile(Request $request, $email)
    {
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();
        if ($User) {
            $Safety = Checkpoints::all();
            $data = [
                "data" => $Safety,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function login(Request $request)
    {
        // Validate the request
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check user credentials
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Generate a token for the user
        // $token = $user->createToken('mobile-app-token')->plainTextToken;
        $token = $user->createToken('mobile-app-token');

        // Return a success response with the token and user information
        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
        ]);
    }

    // Optional: Add a method for logout
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully',
        ]);
    }

    public function mediaMobile(Request $request, $email)
    {
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();
        if ($User) {
            $Safety = Image::all();
            $data = [
                "data" => $Safety,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function profileMobile(Request $request, $email)
    {
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->join('usermeta','usermeta.userId','=','users.id')->first();
        if ($User) {
            $data = [
                "data" => $User,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function alerts(Request $request,$email){
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();
        if ($User) {
            $alerts = Alerts::all();
            $data = [
                "data" => $alerts,
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }
}
