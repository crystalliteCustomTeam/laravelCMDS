<?php

namespace App\Http\Controllers;

use App\Models\Alerts;
use App\Models\Area;
use App\Models\AreaAccident;
use App\Models\AreaUser;
use App\Models\AssignCheckpoint;
use App\Models\Checkpoints;
use App\Models\Image;
use App\Models\LiveFeed;
use App\Models\Notification;
use App\Models\Safety;
use App\Models\SafetyView;
use App\Models\Settings;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\WorkSite;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Http\Resources\WorkSiteResource;

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

    public function validateDevice(Request $request)
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
        $request->validate([
            'device_id' => 'required|string',
            'live_feed_url' => 'required|url',
            'timestamp' => 'required|date',
            'status' => 'required',
        ]);

        if ($area_id == "") {
            $data = [
                "Message" => "Area ID is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }

        $areaExists = Area::find($area_id);

        if (!$areaExists) {
            return response()->json([
                'message' => 'Area not found.',
            ], 404);
        }

        $existingLiveFeed = LiveFeed::where('area_id', $area_id)->first();

        if ($existingLiveFeed) {
            return response()->json([
                'message' => 'Live feed already exists for this area.',
            ], 409);
        }

        $liveFeed = LiveFeed::create([
            'area_id' => $area_id,
            'device_id' => $request->device_id,
            'live_feed_url' => $request->live_feed_url,
            'timestamp' => $request->timestamp,
            'status' => $request->status,
        ]);

        return response()->json([
            'message' => 'LiveFeed stored successfully.',
            'data' => $liveFeed,
        ], 201);
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

    public function getCodeWiseAlert(Request $request)
    {
        if ($request->alert_code == "") {
            $data = [
                "Message" => "Alert Code is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }

        $alerts = Alerts::where('alert_code', $request->alert_code)->get();

        if ($alerts->isEmpty()) {
            $data = [
                "Message" => "No alerts found for the provided alert code",
                "status" => "not_found",
            ];
            return response()->json($data, 404);
        }

        $data = [
            "data" => $alerts,
            "status" => "success",
        ];
        return response()->json($data, 200);
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
        $worksite_id = $request['worksite_id'];
        $captured_image_url = $request['captured_image_url'];
        if ($alert_code == "" || $risk_level == "" || $description == "" || $captured_image_url == "" || $worksite_id == "") {
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
            "worksite_id" => $worksite_id ? $worksite_id : null,
            "captured_image_url" => $captured_image_url,
        ]);

        if ($Alerts) {

            $userData =  AreaUser::where('ARID', $area_id)
                ->join('users', 'users.id', '=', 'areausers.UID')
                ->select('users.fcm_token', 'users.id as UID', 'areausers.id as ARUID')
                ->get();

            $resArr = [
                'title' => $alert_code,
                'MESSAGE' => 'Alert Code: ' . $alert_code . ' Area Code: ' . $area_id . ' '.$description,
            ];

            $data = [
                "Message" => "Alert Created",
                "status" => "success",
            ];

            foreach ($userData as $user) {
                $this->firebaseService->setData($resArr, $user, 'sos');
            }

            return response()->json($data, 200);
        }
    }

    public function worksitealerts(Request $request, $worksite)
    {
        $worksiteString = (string)$worksite;

        $Area = Area::where('WSID', $worksite)
            ->join('alerts', 'alerts.area_code', '=', 'areas.id')
            ->select('alerts.*', 'areas.id as AID')
            ->get();

        $notifications = Notification::whereRaw('JSON_CONTAINS(WSID, ?)', ["\"$worksiteString\""])->get();

        if ($Area->isNotEmpty() || $notifications->isNotEmpty()) {
            $data = [
                "alerts" => $Area,
                "notifications" => $notifications,
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
            $todayWorkSites = WorkSite::whereDate('Start_Date', Carbon::today())->get();
            $upcomingWorkSites = WorkSite::where('Start_Date', '>', Carbon::today())->get();


            // Fetch safety manager details for worksites
            $safetyManagers = DB::table('areausers')
                ->join('users', 'areausers.UID', '=', 'users.id')
                ->join('usermeta', 'users.id', '=', 'usermeta.userId')
                ->select(
                    'areausers.WSID as worksite_id',
                    'users.name as safety_manager_name',
                    'users.email as safety_manager_email',
                    'usermeta.role'
                )
                ->where('usermeta.role', 1) // Only fetch safety managers
                ->orderBy('areausers.updated_at', 'desc') // Order by latest updated manager
                ->get()
                ->groupBy('worksite_id');

            // Attach safety manager to each worksite
            $today = $todayWorkSites->map(function ($worksite) use ($safetyManagers) {
                $safetyManager = $safetyManagers->get($worksite->id)?->first();
                return [
                    "id" => $worksite->id,
                    "Name" => $worksite->Name,
                    "Start_Date" => $worksite->Start_Date,
                    "End_Date" => $worksite->End_Date,
                    "Description" => $worksite->Description,
                    "FeaturedImage" => $worksite->FeaturedImage,
                    "CreateBy" => $worksite->CreateBy,
                    "created_at" => $worksite->created_at,
                    "updated_at" => $worksite->updated_at,
                    "safety_manager" => $safetyManager
                        ? [
                            "name" => $safetyManager->safety_manager_name,
                            "email" => $safetyManager->safety_manager_email,
                        ]
                        : null,
                ];
            });

            $upcoming = $upcomingWorkSites->map(function ($worksite) use ($safetyManagers) {
                $safetyManager = $safetyManagers->get($worksite->id)?->first();
                return [
                    "id" => $worksite->id,
                    "Name" => $worksite->Name,
                    "Start_Date" => $worksite->Start_Date,
                    "End_Date" => $worksite->End_Date,
                    "Description" => $worksite->Description,
                    "FeaturedImage" => $worksite->FeaturedImage,
                    "CreateBy" => $worksite->CreateBy,
                    "created_at" => $worksite->created_at,
                    "updated_at" => $worksite->updated_at,
                    "safety_manager" => $safetyManager
                        ? [
                            "name" => $safetyManager->safety_manager_name,
                            "email" => $safetyManager->safety_manager_email,
                        ]
                        : null,
                ];
            });

            $data = [
                "data" => [
                    "today" => $today,
                    "upcomming" => $upcoming,
                ],
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

    public function worksiteMobiledetailsBk(Request $request, $id)
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

    public function worksiteMobiledetails(Request $request, $id)
    {
        $WorkSite = WorkSite::where('id', $id)->first();

        if (!$WorkSite) {
            return response()->json([
                "Message" => "Worksite not found",
                "status" => "fail",
            ], 404);
        }

        // Fetch Areas related to Worksite
        $Areas = Area::where('WSID', $WorkSite->id)->get();

        // ✅ Fetch Safety Manager assigned to the Worksite (from areausers table)
        $safetyManager = DB::table('areausers')
            ->join('users', 'areausers.UID', '=', 'users.id') // Join users table
            ->join('usermeta', 'users.id', '=', 'usermeta.userId') // Join usermeta for roles
            ->select(
                'users.id as safety_manager_id',
                'users.name as safety_manager_name',
                'users.email as safety_manager_email',
                'usermeta.role'
            )
            ->where('areausers.WSID', $WorkSite->id) // Match worksite
            ->where('usermeta.role', 1) // Ensure user role is safety manager
            ->orderBy('areausers.updated_at', 'desc') // Get latest
            ->first(); // Fetch only one (latest) safety manager

        // Format worksite and area details
        $WorksiteDetail = [
            "Worksite" => [
                "id" => $WorkSite->id,
                "Name" => $WorkSite->Name,
                "Start_Date" => $WorkSite->Start_Date,
                "End_Date" => $WorkSite->End_Date,
                "Description" => $WorkSite->Description,
                "FeaturedImage" => $WorkSite->FeaturedImage ? asset('uploads/' . $WorkSite->FeaturedImage) : null,
                "CreateBy" => $WorkSite->CreateBy,
                "created_at" => $WorkSite->created_at,
                "updated_at" => $WorkSite->updated_at,
                "safety_manager" => $safetyManager ? [
                    "id" => $safetyManager->safety_manager_id,
                    "name" => $safetyManager->safety_manager_name,
                    "email" => $safetyManager->safety_manager_email,
                ] : null,
            ],
            "Areas" => $Areas,
        ];

        return response()->json([
            "data" => $WorksiteDetail,
            "status" => "success",
        ], 200);
    }


    public function worksiteMobilewithareabk(Request $request,$email)
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
            $workSites = WorkSite::with('areas')->get();

            $data = WorkSiteResource::collection($workSites);
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Email Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }


    public function worksiteMobilewitharea(Request $request, $email)
    {
        if (empty($email)) {
            return response()->json([
                "Message" => "Email is required",
                "status" => "fail",
            ], 500);
        }

        $User = User::where('email', $email)->first();

        if ($User) {
            $workSites = DB::table('worksite')
                ->leftJoin('areas', 'worksite.id', '=', 'areas.WSID')
                ->select(
                    'worksite.id as worksite_id',
                    'worksite.name as worksite_name',
                    'areas.id as area_id',
                    'areas.area_name'
                )
                ->get();

            // Fetch safety manager details for each worksite
            $safetyManagers = DB::table('areausers')
                ->join('users', 'areausers.UID', '=', 'users.id')
                ->join('usermeta', 'users.id', '=', 'usermeta.userId')
                ->select(
                    'areausers.WSID as worksite_id',
                    'users.id as safety_manager_id',
                    'users.name as safety_manager_name',
                    'users.email as safety_manager_email',
                    'usermeta.role'
                )
                ->where('usermeta.role', 1) // Only fetch safety managers
                ->orderBy('areausers.updated_at', 'desc') // Order by latest
                ->get()
                ->groupBy('worksite_id'); // Group safety managers by worksite

            // Transform worksite data

            $data = $workSites->groupBy('worksite_id')->map(function ($areas, $worksiteId) use ($safetyManagers) {
                $worksiteName = $areas->first()->worksite_name;
                $safetyManager = $safetyManagers->get($worksiteId)?->first(); // Fetch manager for worksite

                return [
                    'worksite' => [
                        'id' => $worksiteId,
                        'name' => $worksiteName,
                        'safety_manager' => $safetyManager
                            ? [
                                'name' => $safetyManager->safety_manager_name,
                                'email' => $safetyManager->safety_manager_email,
                            ]
                            : null,
                        'areas' => $areas->map(function ($area) {
                            return [
                                'id' => $area->area_id,
                                'name' => $area->area_name,
                            ];
                        })->values(),
                    ],
                ];
            })->values();
            return response()->json($data, 200);
        } else {
            return response()->json([
                "Message" => "Email Not Found",
                "status" => "fail",
            ], 404);
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

    public function readCommunication(Request $request)
    {
        if ($request->notification_id == "") {
            $data = [
                "Message" => "Notification ID is Required",
                "status" => "fail",
            ];
            return response()->json($data, 500);
        }

        $notification = Notification::find($request->notification_id);

        if ($notification) {
            $notification->is_read = 1;
            $notification->save();

            $data = [
                "data" => $notification,
                "status" => "success",
                "Message" => "Notification marked as read",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "Message" => "Notification not found",
                "status" => "not_found",
            ];
            return response()->json($data, 404);
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
            $Safety = Safety::with('safetyView')->get();
            if(Count($Safety) > 0 ){
                $data = [
                    "data" => $Safety,
                    "status" => "success",
                ];
            }else{
                $Safety = Safety::with('safetyView')->get();
                $data = [
                    "data" => $Safety,
                    "status" => "success",
                ];
            }

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
            'fcm_token' => 'required'
        ]);

        // Check user credentials
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials',
            ], 401);
        }

        // Update the user's FCM token
        $user->fcm_token = $request->fcm_token;
        $user->save();

        $USERMETA = UserMeta::where('userId', $user->id)->first();

        // Generate a token for the user
        // $token = $user->createToken('mobile-app-token')->plainTextToken;
        $token = $user->createToken('mobile-app-token');

        // Return a success response with the token and user information
        return response()->json([
            'token' => $token->plainTextToken,
            'user' => $user,
            'meta' => $USERMETA,
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

                if ($User->id && !in_array($User->id, $setusers)) {
                    $setusers[] = $User->id; // Add the current user if not already included
                }

                $request = [
                    "title" => $request['title'],
                    "MESSAGE" => $request['message'],
                    "WSIDS" => json_encode($request['WorksiteID']),
                    "ARIDS" => json_encode($request['AreaID']),
                    "USERS" => json_encode($setusers),
                ];
                $data = $request; // Example data
                /*$url = '/' . date('i:h:s') . '-alerts/';
                $response = $this->firebaseService->setData($data, $User, 'normal');*/

                $users = json_decode($request['USERS'], true);

                $successCount = 0;
                $failureCount = 0;
                $failedUsers = [];

                if (is_array($users)) {
                    $userTokens = User::whereIn('id', $users)->whereNotNull('fcm_token')->get();

                    foreach ($userTokens as $userToken) {
                        $response = $this->firebaseService->setData($data, $userToken, 'normal'); //Send Firebase Cloud Messaging
                        if (isset($response['name'])) {
                            $successCount++;
                        } else {
                            $failureCount++;
                            $failedUsers[] = $userToken->id;
                        }
                    }
                }

                $data = [
                    "Message" => "Notification Created",
                    "status" => "success",
                    "SuccessCount" => $successCount,
                    "FailureCount" => $failureCount,
                    "FailedUsers" => $failedUsers,
                ];
                return response()->json($data, 200);
            } else {
                $data = [
                    "Message" => "Notification Is Not Created Please Contact Developer",
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
            $SafetyView = UserMeta::where('userId', $id)->get();

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

    public function deleteuser(Request $request)
    {
        $id = $request['userID'];
        $user = User::where('id', $id)->first();
        if ($user) {
            User::where('id', $id)->delete();
            $data = [
                "message" => "User Delete",
                "status" => "success",
            ];
            return response()->json($data, 200);
        } else {
            $data = [
                "message" => "User Not Found",
                "status" => "fail",
            ];
            return response()->json($data, 404);
        }
    }

    public function storeAreaAccidents(Request $request)
    {
        $request->validate([
            'accident_code' => 'required|string',
            'description' => 'required|string',
            'severity_level' => 'required|string', // Allow specific values
            'timestamp' => 'required|date',
            'captured_image_url' => 'required|url',
            'reported_by' => 'required|string',
        ]);

        $accident = AreaAccident::create($request->all());

        return response()->json([
            'message' => 'Accident report saved successfully.',
            'data' => $accident,
        ], 201);
    }
}
