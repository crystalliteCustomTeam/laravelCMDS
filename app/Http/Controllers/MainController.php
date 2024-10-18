<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\AreaUser;
use App\Models\Checkpoints;
use App\Models\Image;
use App\Models\Notification;
use App\Models\Safety;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\WorkSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class MainController extends Controller
{
    public function dashboard(Request $request)
    {
        $user = Auth::user();
        return view('dashboard', ["PAGE_TITLE" => "DASHBOARD", "USERNAME" => $user->name]);
    }

    public function GetAllUser(Request $request)
    {
        $user = Auth::user();
        $usersData = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
            ->where('usermeta.role', '!=', '0')
            ->where('usermeta.createBy', '=', $user->id)
            ->select('users.*', 'users.id as UID', 'usermeta.*')
            ->get();
        $images = Image::where('save_image_by', $user->id)->get();

        return view('users', ["PAGE_TITLE" => "USERS", "USERNAME" => $user->name, "USER_DATA" => $usersData, "Images" => $images]);
    }

    public function upload(Request $request)
    {
        $user = Auth::user();
        $name = $request['name'];
        $email = $request['email'];
        $role = $request['role'];
        $USERCOUNT = User::where('email', '=', $email)->count();
        if ($USERCOUNT > 0) {
            return response()->json(["Message" => "Email Found Please Use Different Email"], 500);
        }

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

        $profileImage = $imageName;
        $password = $request['password'];
        $createdBy = $user->id;

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
            "createBy" => $createdBy,
        ]);
        $data = [
            "Message" => "User Created",
            "UserID" => $userMeta->id,
        ];
        return response()->json(['success' => true, $data], 200);

    }

    public function uploadImage(Request $request)
    {
        $user = Auth::user();

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
        echo $userID;
        $user = Auth::user();
        $usersData = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
            ->where('users.id', '=', $userID)
            ->select('users.*', 'usermeta.*')
            ->get();
        return view('useredit', ["PAGE_TITLE" => "EDIT USER", "USERNAME" => $user->name, "USER_DATA" => $usersData]);

    }

    public function worksite(Request $request)
    {

        $user = Auth::user();
        $allsites = WorkSite::where('CreateBy', $user->id)->get();
        $allImages = Image::where('save_image_by',$user->id)->get();
        return view('worksite', ["PAGE_TITLE" => "WORKSITE", "USERNAME" => $user->name, "SITES" => $allsites, "Images" =>$allImages]);

    }

    public function singleworksite(Request $request, $worksiteID)
    {
        $user = Auth::user();
        $worksite = WorkSite::where('CreateBy', $user->id)->where('id', $worksiteID)->first();
        $usersData = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
            ->where('usermeta.role', '!=', '0')
            ->where('usermeta.role', '=', '2')
            ->select('users.*', 'users.id as UID', 'usermeta.*')
            ->get();

        $Areas = Area::where('CreateBy', $user->id)->where('WSID', $worksiteID)->get();

        return view('worksitedetails', ["PAGE_TITLE" => "WORKSITE DETAIL", "USERNAME" => $user->name, 'WORKSITE' => $worksite, 'USERS' => $usersData, 'Areas' => $Areas]);
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
        $users = $request['users'];
        $loginUser = Auth::user();
        $AREAID = $request['AreaID'];

        for ($i = 0; $i < Count($users); $i++) {
            AreaUser::create([
                "WSID" => $users[$i],
                "ARID" => $AREAID,
                "UID" => $loginUser->id,
            ]);
        }

        return redirect()->back();

    }

    public function workarea(Request $request, $id, $area)
    {
        $loginUser = Auth::user();
        $areaDetail = Area::where('CreateBy', $loginUser->id)->where('id', $area)->first();
        $users = AreaUser::where('ARID', $area)
            ->join('users', 'users.id', '=', 'areausers.WSID')
            ->select('users.name as UName', 'users.id as UID')
            ->get();

        return view('areaedit', ["PAGE_TITLE" => "AREA DETAIL EDIT", "USERNAME" => $loginUser->name, 'Areas' => $areaDetail, 'AreaUsers' => $users]);
    }

    public function createWorksite(Request $request)
    {
        $user = Auth::user();
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
            $response = Http::withBody(
                '{
          "title": "' . $resp->title . '",
          "MESSAGE" : "' . $resp->message . '",
          "WSIDS" : "' . json_encode($worksites) . '",
          "ARIDS": "' . json_encode($areas) . '"
        }', 'json'
            )
                ->withHeaders([
                    'Accept' => '*/*',
                    'User-Agent' => 'Thunder Client (https://www.thunderclient.com)',
                    'Content-Type' => 'application/json',
                ])
                ->post('https://webhook.site/2aa264a2-87ce-4b16-b338-5ffa423d806a');

            return redirect()->back();
        } else {
            return response()->json(["Message" => "Notification Not Send"], 500);
        }
    }

    public function notifications(Request $request)
    {
        $loginUser = Auth::user();
        $worksites = WorkSite::where('CreateBy', $loginUser->id)->get();
        $Areas = Area::where('CreateBy', $loginUser->id)->get();
        return view('notifications', ["PAGE_TITLE" => "NOTIFICATION", "USERNAME" => $loginUser->name, "WORKSITE" => $worksites, "AREAS" => $Areas]);
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
        $checkpoint = Checkpoints::where('CreatedBy', $loginUser->id)->get();
        $Safety = Safety::where('CreatedBy', $loginUser->id)->get();
        return view('guidelines', ["PAGE_TITLE" => "SAFETY GUIDELINES ", "USERNAME" => $loginUser->name, "Checkpoint" => $checkpoint, "Safety" => $Safety]);
    }

    public function checkpoint(Request $request)
    {
        $loginUser = Auth::user();
        $checkpoint = Checkpoints::where('CreatedBy', $loginUser->id)->get();
        $allImages = Image::where('save_image_by',$loginUser->id)->get();
        return view('checkpoints', ["PAGE_TITLE" => "CHECKPOINTS", "USERNAME" => $loginUser->name, "checkpoint" => $checkpoint , "Images" => $allImages]);
    }

    public function checkpointCreate(Request $request)
    {
        $loginUser = Auth::user();
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
        $safety = Safety::create([
            "icon" => "car",
            "Images" => "Car",
            "title" => $request['title'],
            "description" => $request['description'],
            "CreatedBy" => $loginUser->id,
        ]);
        if ($safety) {
            return response()->json(["Message" => "Safety Created", "Code" => 200], 200);
        }
        return response()->json(["Message" => "Safety Not Created", "Code" => 500], 500);
    }

    public function media(Request $request)
    {
        $loginUser = Auth::user();
        $allImages = Image::where('save_image_by',$loginUser->id)->get();
        return view('media', ["PAGE_TITLE" => "MEDIA", "USERNAME" => $loginUser->name,"Images"=>$allImages]);
    }

    public function Mediaupload(Request $request)
    {
        $files = $request->file('files');
        $loginUser = Auth::user();
        if ($request->hasFile('files')) {
            foreach ($files as $file) {
                $filename = time() . '-' . $file->getClientOriginalName();
                $file->move(public_path('uploads'), $filename);
                Image::create([
                    'image_path' => 'uploads/' . $filename,
                    'image_title' => $file->getClientOriginalName(),
                    'save_image_by' => $loginUser->id,  // Replace with the correct user info
                ]);
            }

            return response()->json(['success' => 'Files uploaded successfully!']);
        } else {
            return response()->json(['error' => 'No files found!'], 400);
        }
    }


    public function deleteuser(Request $request,$id){
        $user = User::where('id',$id)->delete();
        if($user){
            return redirect()->back();
        }
    }
}
