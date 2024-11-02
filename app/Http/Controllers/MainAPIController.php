<?php

namespace App\Http\Controllers;

use App\Models\Alerts;
use App\Models\Area;
use App\Models\AreaUser;
use App\Models\AssignCheckpoint;
use App\Models\Checkpoints;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Safety;
use App\Models\SafetyView;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\WorkSite;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class MainAPIController extends Controller
{

    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function forgotPassword(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Attempt to send the reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
        ? response()->json(['message' => __($status)], 200)
        : response()->json(['message' => __($status)], 400);
    }

    // Method to handle password reset
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
            }
        );

        return $status === Password::PASSWORD_RESET
        ? response()->json(['message' => __($status)], 200)
        : response()->json(['message' => __($status)], 400);
    }

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

    public function forget(Request $request, $email)
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

        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
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
            $Safety = Safety::join('safetyview', 'safetyview.safetyID', '=', 'safety.id')->get();
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
        $User = User::where('email', $email)->join('usermeta', 'usermeta.userId', '=', 'users.id')->first();
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

    public function alerts(Request $request, $email)
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

    public function createcommunication(Request $request)
    {
        $useremail = $request['email'];
        if ($useremail == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $useremail)->first();
        if ($User) {
            if ($request['title'] == "" || $request['message'] == "" || $request['WorksiteID'] == "" || $request['AreaID'] == "") {
                $data = [
                    "Message" => "Title, Message, WorksiteID, AreaID is required",
                    "status" => "fail",
                ];
                return response()->json($data, 500);
            }
            if (!is_array($request['WorksiteID']) || !is_array($request['AreaID'])) {
                $data = [
                    "Message" => "WorksiteID, AreaID Need Type Array",
                    "status" => "fail",
                ];
                return response()->json($data, 500);
            }

            $Notification = Notification::create([
                "title" => $request['title'],
                "message" => $request['message'],
                "WSID" => json_encode($request['WorksiteID']),
                "ARIDS" => json_encode($request['AreaID']),
            ]);

            if ($Notification) {
                $areas = $request['AreaID'];
                $setusers = [];
                foreach ($areas as $arId) {
                    $allusers = AreaUser::where('ARID', $arId)->get();
                    foreach ($allusers as $user) {
                        array_push($setusers, $user->UID);
                    }
                }

                $request = [
                    "title" => $request['title'],
                    "MESSAGE" => $request['message'],
                    "WSIDS" => json_encode($request['WorksiteID']),
                    "ARIDS" => json_encode($request['AreaID']),
                    "USERS" => json_encode($setusers),
                ];
                $data = $request; // Example data
                $url = '/' . date('i:h:s') . '-alerts/';
                $response = $this->firebaseService->setData($url, $data);

                $data = [
                    "Message" => "Notfication Created",
                    "status" => "success",
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    "Message" => "Notfication Is Not Created Please Contact Developer",
                    "status" => "fail",
                ];
                return response()->json($data, 500);
            }

        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function settings(Request $request, $email)
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
            $alerts = Settings::where('userId', $User->id)->get();

            if ($alerts) {
                $data = [
                    "data" => $alerts,
                    "status" => "success",
                ];
                return response()->json($data, 200);
            }
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);

        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function settingsUpdate(Request $request)
    {
        $email = $request['email'];
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();
        if ($User) {
            $setting = Settings::where('userId', $User->id)->first();
            if ($setting) {
                $alerts = Settings::where('id', $setting->id)->update([
                    "pushNotification" => $request['pushNotification'],
                    "emailNotfication" => $request['emailNotfication'],
                    "locationServices" => $request['locationServices'],
                ]);
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

        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }

    }

    public function profileupdate(Request $request)
    {
        $email = $request['email'];
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();
        if ($User) {
            $hashed = Hash::make($request['password']);

            $User = User::where('email', $email)->update([
                "email" => $email,
                "name" => $request['name'],
                "password" => $hashed,
            ]);

            $data = [
                "Message" => $User,
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

    public function registerUser(Request $request)
    {

        $data = $this->firebaseService->getData('/');
        return response()->json($data);

    }

    public function writeUserData(Request $request)
    {
        $request = [
            "title" => $request['title'],
            "MESSAGE" => $request['MESSAGE'],
            "WSIDS" => $request['WSIDS'],
            "ARIDS" => $request['ARIDS'],
            "USERS" => $request['USERS'],
        ];
        $data = $request; // Example data
        $url = '/' . date('i:h:s') . '-alerts/';
        $response = $this->firebaseService->setData($url, $data);
        return response()->json($response);
    }

    public function safetyview(Request $request)
    {
        $email = $request['email'];
        if ($email == "") {
            $data = [
                "Message" => "Email is required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }
        $User = User::where('email', $email)->first();
        if ($User) {
            $safetyID = $request['safetyID'];
            $userId = $request['userId'];
            $SafetyView = SafetyView::create([
                "safetyID" => $safetyID,
                "userId" => $userId,
            ]);
            if ($SafetyView == "") {
                $data = [
                    "Message" => "View Added",
                    "status" => "success",
                ];
                return response()->json($data, 200);
            }
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function safetyuserCount(Request $request, $id)
    {

        $User = SafetyView::where('safetyID', $id)->first();
        if ($User) {
            $usersMeta = $request['safetyID'];
            $SafetyView = UserMeta::where('userId',$id)->get();

            $data = [
                "Message" => $SafetyView,
                "status" => "success",
            ];
            return response()->json($data, 200);

        } else {
            $data = [
                "Message" => "Id Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }
}
