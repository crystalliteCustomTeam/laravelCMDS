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
use App\Models\Settings;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\WorkSite;
use App\Services\FirebaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class MainController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseService $firebaseService = null)
    {
        $this->firebaseService = $firebaseService ?? new FirebaseService();
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage')->first();

        $userCount = User::count();
        $ws_count = WorkSite::where('CreateBy', $user->id)->count();
        $NotificationCount = Notification::count();
        $alerts = WorkSite::select('worksite.Name', DB::raw('COUNT(alerts.id) as alerts_count'))
            ->join('areas', 'areas.WSID', '=', 'worksite.id')
            ->join('alerts', 'alerts.area_code', '=', 'areas.id')
            ->groupBy('worksite.Name')
            ->get();

        return view('dashboard', ["PAGE_TITLE" => "DASHBOARD", "USERNAME" => $user->name, "USERCOUNT" => $userCount, 'WORKSITE_COUNT' => $ws_count, "NotificationCount" => $NotificationCount, "UFM" => $usermetaFM, 'RISKS' => $alerts]);
    }

    public function getAlertsData()
    {
        // Count alerts grouped by the day of the week
        $alerts = DB::table('alerts')
            ->select(DB::raw('DAYNAME(created_at) as day'), DB::raw('COUNT(*) as count'))
            ->groupBy('day')
            ->pluck('count', 'day')
            ->toArray();

// Set default values for each day of the week
        $daysOfWeek = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        $data = [];

        foreach ($daysOfWeek as $day) {
            $data[$day] = $alerts[$day] ?? 0; // Use 0 if there are no alerts for a day
        }

        return response()->json($data);
    }

    public function GetAllUser(Request $request)
    {
        $user = Auth::user(); // Get logged-in user
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage')->first();

        // Check if the logged-in user is a Super Admin
        $isSuperAdmin = is_null(UserMeta::where('userId', $user->id)->value('role'));

        // Fetch users
        $usersData = User::leftJoin('usermeta', 'users.id', '=', 'usermeta.userId')
            ->when(!$isSuperAdmin, function ($query) use ($user) {
                // If not a Super Admin, exclude Super Admins and fetch users created by the logged-in user
                return $query->where('usermeta.role', '!=', '0') // Exclude workers with role = 0
                             ->whereNotNull('usermeta.role')      // Exclude Super Admins
                             ->where('usermeta.createBy', '=', $user->id);
            })
            ->when($isSuperAdmin, function ($query) {
                // If a Super Admin, include all users
                return $query;
            })
            ->select('users.*', 'users.id as UID', 'usermeta.role', 'usermeta.createBy', 'usermeta.featuredImage')
            ->orderByRaw('CASE WHEN usermeta.role IS NULL THEN 0 ELSE 1 END') // Super Admins first
            ->orderBy('users.id') // Secondary order by user ID
            ->get();

        $images = Image::where('save_image_by', $user->id)->get();

        return view('users', [
            "PAGE_TITLE" => "USERS",
            "USERNAME" => $user->name,
            "USER_DATA" => $usersData,
            "Images" => $images,
            "UFM" => $usermetaFM,
        ]);
    }




    public function upload(Request $request)
    {
        $user = Auth::user();
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage')->first();
        $name = $request['name'];
        $email = $request['email'];
        $role = $request['role'];
        $profileImage = $request['FeaturedImage'];

        $password = $request['password'];
        $USERCOUNT = User::where('email', '=', $email)->count();
        if ($USERCOUNT > 0) {
            return response()->json(["Message" => "Email Found Please Use Different Email"], 500);
        }

        if ($name == "" || $email == "" || $role == "" || $profileImage == "") {
            return response()->json(["Message" => "Cannot Leave Feild Blank "], 500);
        }

        $HashPassword = Hash::make($password, [
            'rounds' => 12,
        ]);

        $UserID = User::create([
            "name" => $name,
            "email" => $email,
            "password" => $HashPassword,
        ]);

        $userMeta = UserMeta::create([
            "userId" => $UserID->id,
            "featuredImage" => $profileImage,
            "role" => $role,
            "createBy" => $user->id,
        ]);
        $Settings = Settings::create([
            "userId" => $UserID->id,
            "pushNotification" => 0,
            "emailNotfication" => 0,
            "locationServices" => 0,
        ]);

        $data = [
            "Message" => "User Created",
            "UserID" => $userMeta->id,
        ];
        return response()->json(['Code' => 200], 200);

    }

    public function uploadImage(Request $request)
    {
        $user = Auth::user();
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage')->first();

        $request->validate([
            'image' => 'required|mimes:jpeg,jpg,png|max:2048',
        ]);

        // Handle file upload
        $image = $request->file('image');
        $imageName = time() . '.' . $image->extension();
        $image->move(public_path('images'), $imageName);

        // Save image path and save_image_by to database
        $imageRecord = Image::create([
            'image_path' => $imageName,
            'save_image_by' => $user->id, // Save the user or identifier
        ]);

        return response()->json(['success' => true, 'image' => $imageName, 'name' => $imageName]);
    }

    public function EditUser(Request $request, $userID)
    {
        $user = Auth::user();

        // Fetch UserMeta for logged-in user
        $usermetaFM = UserMeta::where('userId', $user->id)
            ->select('featuredImage')
            ->first();

        // Fetch the specific user and their meta
        $usersData = User::leftJoin('usermeta', 'users.id', '=', 'usermeta.userId')
            ->where('users.id', '=', $userID)
            ->select('users.*', 'users.id as UID', 'usermeta.role', 'usermeta.featuredImage')
            ->first(); // Ensure a single record is fetched

        // Check if the user exists
        if (!$usersData) {
            return redirect()->back()->withErrors(['error' => 'User not found.']);
        }

        // Fetch all images
        $Images = Image::where('save_image_by', $user->id)->get();

        // Pass data to the view
        return view('useredit', [
            "PAGE_TITLE" => "EDIT USER",
            "USERNAME" => $user->name,
            "USER_DATA" => $usersData, // Pass the single user object
            "Images" => $Images,
            "UFM" => $usermetaFM,
        ]);
    }





    public function worksite(Request $request)
    {

        $user = Auth::user();
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage', 'role')->first();

        $allsites = WorkSite::when($usermetaFM->role, function ($query) use ($user) {
                    return $query->where('CreateBy', $user->id);
            })
            ->with('areaUsers')
            ->withCount('areaUsers') // Count of AreaUsers
            ->get();
        $allImages = Image::where('save_image_by', $user->id)->get();

        // $usersCount = AreaUser::select('areas.*', 'areausers.id as AreaID', DB::raw('COUNT(areausers.id) as USERSCOUNT'))
        // ->join('areausers', 'areausers.ARID', '=', 'areas.id')
        // ->get();
        // echo "<pre>";
        // print_r($usersCount);
        // die();

        foreach ($allsites as $SITE) {
            $SITE->total_area_accidents = AreaUser::whereIn('ARID', $SITE->areaUsers->pluck('ARID'))
                ->join('area_accidents', 'area_accidents.area_id', '=', 'areausers.ARID')
                ->where('areausers.WSID', $SITE->id)
                ->count();
        }

        return view('worksite', ["PAGE_TITLE" => "WORKSITE", "USERNAME" => $user->name, "SITES" => $allsites, "Images" => $allImages, "UFM" => $usermetaFM]);

    }

    public function countNotification($id)
    {
        $count = Notification::whereJsonContains('WSID', (string) $id)->count();
        return $count;
    }

    public function countalert($id)
    {

        $count = Area::where('WSID', $id)->first();
        if ($count != null) {
            $alertCount = Alerts::where('area_code', $count->id)->count();
            return $alertCount;
        }
        return 0;

    }

    public function singleworksite(Request $request, $worksiteID)
    {
        $user = Auth::user();
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage')->first();
        $worksite = WorkSite::where('CreateBy', $user->id)->where('id', $worksiteID)->first();
        $usersData = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
            ->where('usermeta.role', '=', '2')
            ->select('users.*', 'users.id as UID', 'usermeta.*')
            ->get();

        $Areas = Area::where('CreateBy', $user->id)->where('WSID', $worksiteID)->get();
        $frontalert = [];
        foreach ($Areas as $area) {
            $Alerts = Alerts::where('area_code', $area->id)->get();
            array_push($frontalert, $Alerts);
        }

        $areaIds = AreaUser::where('WSID', $worksiteID)
            ->pluck('ARID');

        $frontalert = Alerts::whereIn('area_code', $areaIds)->get();

        $allImages = Image::where('save_image_by', $user->id)->get();

        return view('worksitedetails', ["PAGE_TITLE" => "WORKSITE DETAIL", "USERNAME" => $user->name, 'WORKSITE' => $worksite, 'USERS' => $usersData, 'Areas' => $Areas, "UFM" => $usermetaFM, "Images" => $allImages, "frontalert" => $frontalert]);
    }

    public function worksiteEdit(Request $request)
    {
        $siteID = $request['siteId'];
        $enddate = WorkSite::where('id', $siteID)->select('End_Date')->first();
        WorkSite::where('id', $siteID)->update([
            "Name" => $request['name'],
            "Description" => $request['description'],
            "FeaturedImage" => $request['FeaturedImage'],
            "Start_Date" => $request['startDate'],
            "End_Date" => ($request['enddate'] == "") ? $enddate->End_Date : $request['enddate'],
        ]);

        return redirect()->back();
    }

    public function area(Request $request)
    {
        $area_name = $request['area_name'];
        $area_O_D_ID = $request['O_D_ID'];
        $area_O_D_KEY = $request['O_D_KEY'];

        if ($area_name == "" || $area_O_D_ID == "" || $area_O_D_KEY == "") {
            return response()->json(["Message" => "Cannot Leave Feild Blank "], 500);
        }

        $user = Auth::user();
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage')->first();
        $area = Area::create([
            "WSID" => $request['WSID'],
            "CreateBy" => $user->id,
            "Area_Name" => $area_name,
            "Orin_Device_ID" => $area_O_D_ID,
            "Orin_Device_Key" => $area_O_D_KEY,
        ]);
        if ($area) {
            return response()->json(["Message" => "Area Created", "AID" => $area->id, "Code" => 200], 200);
        }
    }

    public function areaUserAssign(Request $request)
    {
        try {
            $users = $request['users'];
            $loginUser = Auth::user();

            $AREAID = $request['AreaID'];
            $workSiteId = Area::where('id', $AREAID)->select('WSID')->first();

            for ($i = 0; $i < count($users); $i++) {
                AreaUser::create([
                    "WSID" => $workSiteId->WSID,
                    "ARID" => $AREAID,
                    "UID" => $users[$i],
                ]);
            }

            return response()->json([
                'Code' => 200,
                'Message' => 'Users assigned to the area successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'Code' => 500,
                'Message' => 'An error occurred while assigning users. Please try again later.',
            ], 500);
        }
    }


    public function worksiteDelete(Request $request, $id)
    {
        WorkSite::where('id', $id)->delete();
        return redirect()->back();
    }

    public function workarea(Request $request, $id, $area)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        $areaDetail = Area::where('CreateBy', $loginUser->id)->where('id', $area)->first();

        $users = AreaUser::where('ARID', $area)
            ->join('users', 'users.id', '=', 'areausers.UID')
            ->join('usermeta', 'users.id', '=', 'usermeta.userId')
            ->select('users.name as UName', 'users.id as UID', 'areausers.id as ARUID', 'usermeta.role')
            ->get();

        $Allusers = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
            //->where('usermeta.createBy', $loginUser->id)
            ->whereIn('usermeta.role', [1, 2])
            ->select('users.*', 'users.id as UID', 'usermeta.id as UMID', 'usermeta.role')
            ->get();

        // Separate workers (role = 2) and safety managers (role = 1)
        $workers = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
            ->where('usermeta.role', 2)
            ->select('users.*', 'users.id as UID', 'usermeta.id as UMID', 'usermeta.role')
            ->get();

        $safetyManagers = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
            ->where('usermeta.role', 1)
            ->select('users.*', 'users.id as UID', 'usermeta.id as UMID', 'usermeta.role')
            ->get();

        return view('areaedit', [
            "PAGE_TITLE" => "AREA DETAIL EDIT",
            "USERNAME" => $loginUser->name,
            'Areas' => $areaDetail,
            'AreaUsers' => $users,
            'Workers' => $workers,
            'SafetyManagers' => $safetyManagers,
            "UFM" => $usermetaFM,
        ]);

    }

    public function createWorksite(Request $request)
    {
        $user = Auth::user();
        $usermetaFM = UserMeta::where('userId', $user->id)->select('featuredImage')->first();
        $Name = $request['site_name'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        $description = $request['description'];
        $featuredImage = $request['FeaturedImage'];
        $CreateBy = $user->id;

        if ($Name == "" || $start_date == "" || $end_date == "" || $description == "" || $featuredImage == "") {
            return response()->json(["Message" => "Cannot Leave Feild Blank "], 500);
        }

        $workSite = WorkSite::create([
            "Name" => $Name,
            "Start_Date" => $start_date,
            "End_Date" => $end_date,
            "Description" => $description,
            "FeaturedImage" => $featuredImage,
            "CreateBy" => $CreateBy,
        ]);

        if ($workSite) {
            return response()->json(["Message" => "WorkSite Created", "Code" => 200], 200);
        } else {
            return response()->json(["Message" => "Error While Creating Worksite"], 500);
        }

    }

    public function notificationsSend(Request $request)
    {
        $worksites = $request['worksiteID'];
        $areas = $request['areas'];
        $notificationID = $request['notificationID'];

        $Notfication = Notification::where('id', $notificationID)
            ->update([
                'WSID' => json_encode($worksites),
                'ARIDS' => json_encode($areas),
            ]);

        if ($Notfication) {

            $resp = Notification::where('id', $notificationID)->first();
            $setusers = [];
            foreach ($areas as $arId) {
                $allusers = AreaUser::where('ARID', $arId)->get();
                foreach ($allusers as $user) {
                    array_push($setusers, $user->UID);
                }
            }

            $currentUser = Auth::user(); // Get the currently authenticated user
            if ($currentUser && !in_array($currentUser->id, $setusers)) {
                $setusers[] = $currentUser->id; // Add the current user if not already included
            }

            $firebaseData = [
                "title" => $resp->title,
                "MESSAGE" => $resp->message,
                "WSIDS" => json_encode($worksites),
                "ARIDS" => json_encode($areas),
                "USERS" => json_encode($setusers),
            ];

            $users = json_decode($firebaseData['USERS'], true);

            $successCount = 0;
            $failureCount = 0;
            $failedUsers = [];

            if (is_array($users)) {
                $userTokens = User::whereIn('id', $users)->whereNotNull('fcm_token')->get();

                foreach ($userTokens as $userToken) {
                    $response = $this->firebaseService->setData($firebaseData, $userToken, 'normal'); //Send Firebase Cloud Messaging

                    if (isset($response['name'])) {
                        $successCount++;
                    } else {
                        $failureCount++;
                        $failedUsers[] = $userToken->id;
                    }
                }
            }

            if ($failureCount > 0) {
                return response()->json([
                    "Code" => 206,
                    "Message" => "Notification partially sent",
                    "SuccessCount" => $successCount,
                    "FailureCount" => $failureCount,
                    "FailedUsers" => $failedUsers,
                ], 206);
            } else {
                return response()->json([
                    "Code" => 200,
                    "Message" => "Notification sent successfully to all users",
                    "SuccessCount" => $successCount,
                ], 200);
            }
        } else {
            return response()->json(["Message" => "Notification Not Sent"], 500);
        }

            /*$ch = curl_init('https://dashboard.vnexia.com/api/sendnotification');

            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return the response as a string
            curl_setopt($ch, CURLOPT_POST, true); // Set the request method to POST
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($firebaseData)); // Send the data

            $response = curl_exec($ch);

            if (curl_errno($ch)) {
                echo 'cURL error: ' . curl_error($ch);
            } else {
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE); // Get HTTP status code
                if ($httpCode == 200) {
                    echo "Data sent successfully!";
                    // Optionally redirect or return
                    return redirect()->back();
                } else {
                    echo "Failed to send data. Status Code: " . $httpCode . ". Response: " . $response;
                }
            }

            curl_close($ch);

        } else {
            return response()->json(["Message" => "Notification Not Send"], 500);
        }*/
    }

    public function notifications(Request $request)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage', 'role')->first();
        $worksites = WorkSite::when($usermetaFM->role, function ($query) use ($loginUser) {
                    return $query->where('CreateBy', $loginUser->id);
                    })->get();
        $Areas = Area::when($usermetaFM->role, function ($query) use ($loginUser) {
                    return $query->where('CreateBy', $loginUser->id);
                 })->get();
        $AllNotifications = Notification::all();
        // $Notifications = [];
        // foreach ($AllNotifications as $AllNotification) {
        //     $WSID = $AllNotification->WSID;
        //     if ($WSID == 0 || $WSID == "") {
        //         continue;
        //     } else {
        //         $jsonIDs = json_decode($WSID);
        //         foreach($jsonIDs as $JSID){
        //             echo $JSID;
        //         }
        //     }
        // }
        return view('notifications', ["PAGE_TITLE" => "NOTIFICATION", "USERNAME" => $loginUser->name, "WORKSITE" => $worksites, "AREAS" => $Areas, "AllNotification" => $AllNotifications, "UFM" => $usermetaFM]);
    }

    public function notificationsCreate(Request $request)
    {
        $title = $request['title'];
        $message = $request['message'];

        $notification = Notification::create([
            'title' => $title,
            'message' => $message,
            'WSID' => 0,
            'ARIDS' => "0",
        ]);

        if ($notification) {

            return response()->json(["Message" => "Area Created", "NID" => $notification->id, "Code" => 200], 200);
        }

    }

    public function guide(Request $request)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage', 'role')->first();
        $checkpoint = Checkpoints::when($usermetaFM->role, function ($query) use ($loginUser) {
            return $query->where('CreatedBy', $loginUser->id);
        })->get();
        $Safety = Safety::when($usermetaFM->role, function ($query) use ($loginUser) {
            return $query->where('CreatedBy', $loginUser->id);
        })->get();
        $allImages = Image::where('save_image_by', $loginUser->id)->get();
        return view('guidelines', ["PAGE_TITLE" => "SAFETY GUIDELINES ", "USERNAME" => $loginUser->name, "Checkpoint" => $checkpoint, "Safety" => $Safety, "Images" => $allImages, "UFM" => $usermetaFM]);
    }

    public function checkpoint(Request $request)
    {
        $loginUser = Auth::user();

            $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
            $checkpoint = Checkpoints::where('CreatedBy', $loginUser->id)->get();
            $allImages = Image::where('save_image_by', $loginUser->id)->get();
            return view('checkpoints', ["PAGE_TITLE" => "CHECKPOINTS", "USERNAME" => $loginUser->name, "checkpoint" => $checkpoint, "Images" => $allImages, "UFM" => $usermetaFM]);


    }

    public function checkpointCreate(Request $request)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        $checkpoint = Checkpoints::create([
            "title" => $request['title'],
            "Description" => $request['description'],
            "Images" => $request['FeaturedImage'],
            "Videos" => ($request['videoURL'] == "") ? "NO URL ADDED" : $request['videoURL'],
            "CreatedBy" => $loginUser->id,
        ]);
        if ($checkpoint) {
            return response()->json(["Message" => "Checkpoint", "Code" => 200], 200);
        }
        return response()->json(["Message" => "Checkpoint Not Created", "Code" => 500], 500);
    }

    public function guideCreate(Request $request)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        $safety = Safety::create([
            "icon" => "car",
            "Images" => $request['FeaturedImage'],
            "title" => $request['title'],
            "description" => $request['description'],
            "CreatedBy" => $loginUser->id,
        ]);
        if ($safety) {
            return response()->json(["Message" => "Safety Created", "Safety_ID" => $safety->id, "Code" => 200], 200);
        }
        return response()->json(["Message" => "Safety Not Created", "Code" => 500], 500);
    }

    public function guideAssign(Request $request)
    {
        $request->validate([
            'safety_id' => 'required',     // Ensure safety_id is provided
            'checkpoint' => 'required', // Ensure checkpoint is an array
        ]);

        $checkpoints = $request['checkpoint'];
        $safetyID = $request['safety_id'];
        for ($i = 0; $i < Count($checkpoints); $i++) {
            AssignCheckpoint::create([
                "SAFID" => $safetyID,
                "CHKID" => $checkpoints[$i],
            ]);
        }

        return response()->json([
            'Message' => 'Checkpoints successfully assigned to the guideline.',
            'Code' => 200,
        ], 200);
    }

    public function media(Request $request)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        $allImages = Image::where('save_image_by', $loginUser->id)->get();
        return view('media', ["PAGE_TITLE" => "MEDIA", "USERNAME" => $loginUser->name, "Images" => $allImages, "UFM" => $usermetaFM]);
    }

    public function Mediaupload(Request $request)
    {
        $files = $request->file('files');
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        if ($request->hasFile('files')) {
            foreach ($files as $file) {
                $filename = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                Image::create([
                    'image_path' => 'uploads/' . $filename,
                    'image_title' => $file->getClientOriginalName(),
                    'save_image_by' => $loginUser->id, // Replace with the correct user info
                ]);
            }

            return response()->json(['success' => 'Files uploaded successfully!']);
        } else {
            return response()->json(['error' => 'No files found!'], 400);
        }
    }

    public function deleteuser(Request $request, $id)
    {
        $user = User::where('id', $id)->delete();
        if ($user) {
            return redirect()->back();
        }
    }

    public function mediaDelete(Request $request, $id)
    {
        $imageID = $id;
        $imageStatus = Image::where('id', $id)->delete();
        if ($imageStatus) {
            return redirect()->back();
        } else {
            return redirect()->back();
        }
    }

    public function EditUserPOST(Request $request)
    {
        $name = $request['name'];
        $email = $request['email'];
        $role = $request['role'];
        $FeaturedImage = $request['FeaturedImage'];
        $userID = $request['userID'];
        try {
            $user = User::where('id', $userID)->update([
                'name' => $name,
                'email' => $email,
            ]);
            if ($user) {
                try {
                    $UserMeta = UserMeta::where('userId', $userID)->update([
                        "featuredImage" => $FeaturedImage,
                        'role' => $role,
                    ]);
                    return response()->json(['Message' => 'User Updated', 'Code' => 200], 200);
                } catch (Exception $d) {
                    return response()->json(['Message' => 'User Not Updated' . $e, 'Code' => 500], 500);
                }
            }
        } catch (Exception $e) {
            return response()->json(['Message' => 'User Not Updated' . $e, 'Code' => 500], 500);
        }

    }

    public function checkpointEdit(Request $request, $ID)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        $allImages = Image::where('save_image_by', $loginUser->id)->get();
        $checkpoint = Checkpoints::where('CreatedBy', $loginUser->id)->where('id', $ID)->first();
        return view('checkpointEdit', ["PAGE_TITLE" => "MEDIA EDIT CHECKPOINT", "USERNAME" => $loginUser->name, "Images" => $allImages, "checkpoint" => $checkpoint, "UFM" => $usermetaFM]);
    }

    public function checkpointEditPOST(Request $request)
    {
        $ID = $request['checkpointID'];
        $Video = $request['video'];
        $title = $request['title'];
        $FeaturedImage = $request['FeaturedImage'];
        $description = $request['description'];

        $Checkpoints = Checkpoints::where('id', $ID)->update([
            "title" => $title,
            "Description" => $description,
            "Images" => $FeaturedImage,
            "Videos" => $Video,
        ]);
        return redirect()->back();

    }

    public function checkpointDelete(Request $request, $id)
    {
        Checkpoints::where('id', $id)->delete();
        return redirect()->back();
    }

    public function guideDelete(Request $request, $id)
    {
        Safety::where('id', $id)->delete();

        return redirect()->back();
    }

    public function guideEdit(Request $request, $id)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        $allImages = Image::where('save_image_by', $loginUser->id)->get();
        $checkpoint = Checkpoints::where('CreatedBy', $loginUser->id)->get();
        $Safety = Safety::where('id', $id)->first();
        $checkpointSaf = AssignCheckpoint::where('safety_checkpoint.SAFID', $Safety->id)->join('checkpoints', 'safety_checkpoint.CHKID', '=', 'checkpoints.id')->get();
        return view('guidlineEdit', ["PAGE_TITLE" => "EDIT SAFETY", "USERNAME" => $loginUser->name, "Images" => $allImages, "Checkpoint" => $checkpoint, "UFM" => $usermetaFM, 'Safety' => $Safety, "checkpointSaf" => $checkpointSaf]);
    }

    public function notificationsDelete($ID)
    {
        Notification::where('id', $ID)->delete();
        return redirect()->back();
    }

    public function worksiteDetail($id)
    {
        $loginUser = Auth::user();
        $usermetaFM = UserMeta::where('userId', $loginUser->id)->select('featuredImage')->first();
        $allImages = Image::where('save_image_by', $loginUser->id)->get();
        $worksites = WorkSite::where('CreateBy', $loginUser->id)->where('id', $id)->first();
        $Areas = Area::where('CreateBy', $loginUser->id)->where('WSID', $worksites->id)->get();
        return view('worksiteDetail', ["PAGE_TITLE" => "EDIT SAFETY", "USERNAME" => $loginUser->name, "Images" => $allImages, "worksites" => $worksites, "Areas" => $Areas, "UFM" => $usermetaFM]);
    }

    public function workareaDelete($id, $areaCode)
    {
        Area::where('id', $areaCode)->delete();
        return redirect()->back();
    }

    public function areaEdit(Request $request)
    {
        $area_name = $request['area_name'];
        $Orin_Device_ID = $request['Orin_Device_ID'];
        $Orin_Device_Key = $request['Orin_Device_Key'];
        $area_id = $request['area_id'];

        $Area = Area::where('id', $area_id)->update([
            "Area_Name" => $area_name,
            'Orin_Device_ID' => $Orin_Device_ID,
            "Orin_Device_Key" => $Orin_Device_Key,
        ]);

        if ($Area) {
            return redirect()->back();
        }
    }

    public function areaUserRemove($id)
    {
        try {
            AreaUser::where('id', $id)->delete();

            return redirect()->back()->with('success', 'User removed successfully!');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to remove user. Please try again.');
        }
    }

    public function safetyUpdate(Request $request)
    {
        $FeaturedImage = $request['FeaturedImage'];
        $safety_title = $request['safety_title'];
        $description = $request['description'];
        $id = $request['safety_id'];
        $Safety = Safety::where('id', $id)->update([
            'title' => $safety_title,
            'Images' => $FeaturedImage,
            'description' => $description,
        ]);

        // dd($request);

        // //echo $safety;
        if ($Safety) {
            return redirect()->back();
        }

    }

    public function uploadtab(Request $request)
    {
        $files = $request->file('files');
        $uploadedImages = [];
        $loginUser = Auth::user();

        foreach ($files as $file) {
            $filename = time() . '-' . $file->getClientOriginalName();
            $file->move(public_path('uploads'), $filename);
            $image = Image::create([
                'image_path' => 'uploads/' . $filename,
                'image_title' => $file->getClientOriginalName(),
                'save_image_by' => $loginUser->id, // Replace with the correct user info
            ]);

            $uploadedImages[] = [
                'image_path' => 'uploads/' . $filename,
                'image_title' => $image->image_title,
            ];
        }

        return response()->json(['uploadedImages' => $uploadedImages]);
    }

}
