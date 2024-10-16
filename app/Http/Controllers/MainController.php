<?php

namespace App\Http\Controllers;

use App\Models\Image;
use App\Models\User;
use App\Models\UserMeta;
use App\Models\WorkSite;
use App\Models\Area;
use App\Models\AreaUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

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
            ->select('users.*','users.id as UID' , 'usermeta.*')
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
        return response()->json(['success' => true,$data], 200);

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
        $imageRecord  = Image::create([
            'image_path' => $imageName,
            'save_image_by' => $user->id, // Save the user or identifier
        ]);

        
        return response()->json(['success' => true, 'image' => $imageName, 'name' => $imageName]);
    }


    public function EditUser(Request $request,$userID){
        echo $userID;
        $user = Auth::user();
        $usersData = User::join('usermeta', 'users.id', '=', 'usermeta.userId') 
        ->where('users.id', '=', $userID)
        ->select('users.*', 'usermeta.*')
        ->get();
        return view('useredit', ["PAGE_TITLE" => "EDIT USER", "USERNAME" => $user->name, "USER_DATA" => $usersData]);
        
    }

    public function worksite(Request $request){
        
        $user = Auth::user();
        $allsites = WorkSite::where('CreateBy',$user->id)->get();
        return view('worksite', ["PAGE_TITLE" => "WORKSITE", "USERNAME" => $user->name,"SITES"=>$allsites]);
        
    }

    public function singleworksite(Request $request,$worksiteID){
        $user = Auth::user();
        $worksite = WorkSite::where('CreateBy',$user->id)->where('id',$worksiteID)->first();
        $usersData = User::join('usermeta', 'users.id', '=', 'usermeta.userId')
        ->where('usermeta.role', '!=', '0')
        ->where('usermeta.role', '=', '2')
        ->select('users.*','users.id as UID' , 'usermeta.*')
        ->get();

        $Areas = Area::where('CreateBy',$user->id)->where('WSID',$worksiteID)->get();
        
        return view('worksitedetails', ["PAGE_TITLE" => "WORKSITE DETAIL", "USERNAME" => $user->name,'WORKSITE'=>$worksite,'USERS' => $usersData,'Areas' => $Areas]);
    }

    public function area(Request $request){
        $area_name = $request['area_name'];
        $area_O_D_ID = $request['O_D_ID'];
        $area_O_D_KEY = $request['O_D_KEY'];

        if($area_name == "" || $area_O_D_ID == "" || $area_O_D_KEY == ""){
            return response()->json(["Message" => "Cannot Leave Feild Blank "], 500);
        }

        $user = Auth::user();
        $area = Area::create([
            "WSID" =>	$request['WSID'],
            "CreateBy" => $user->id,
            "Area_Name" =>	$area_name,
            "Orin_Device_ID" =>	$area_O_D_ID,
            "Orin_Device_Key" => $area_O_D_KEY
        ]);
        if($area){
            return response()->json(["Message" => "Area Created","AID"=>$area->id,"Code"=>200], 200);
        }
    }


    public function areaUserAssign(Request $request){
        $users = $request['users'];
        $loginUser = Auth::user();
        $AREAID = $request['AreaID'];
        
        for($i=0; $i < Count($users) ; $i++){
            AreaUser::create([
                "WSID" =>	$users[$i],
                "ARID" =>	$AREAID,
                "UID" =>	$loginUser->id,
            ]);
        }

        return redirect()->back();
        
    }

    public function workarea(Request $request , $id,$area){
        $loginUser = Auth::user();
        $areaDetail =  Area::where('CreateBy',$loginUser->id)->where('id',$area)->first();
        $users = AreaUser::where('ARID',$area)
        ->join('users','users.id','=','areausers.WSID')
        ->select('users.name as UName','users.id as UID')
        ->get();
        print_r($users);
        die();
        return view('areaedit', ["PAGE_TITLE" => "AREA DETAIL EDIT", "USERNAME" => $loginUser->name,'Areas' => $areaDetail,'AreaUsers'=>$users]);
    }


    public function createWorksite(Request $request){
        $user = Auth::user();
        $Name = $request['site_name'];
        $start_date  = $request['start_date'];
        $end_date = $request['end_date'];
        $description = $request['description'];
        $CreateBy = $user->id;

        if($Name == "" || $start_date == "" || $end_date == "" || $description =="" ){
            return response()->json(["Message" => "Cannot Leave Feild Blank "], 500);
        }

        $workSite = WorkSite::create([
            "Name" => $Name,
            "Start_Date" => $start_date,
            "End_Date" => $end_date,
            "Description" => $description,
            "CreateBy" => $CreateBy
        ]);

        if($workSite){
            return response()->json(["Message" => "WorkSite Created"], 200);
        }else{
            return response()->json(["Message" => "Error While Creating Worksite"], 500);
        }

    }
}
