<?php

namespace App\Http\Controllers;

use App\Category;
use App\Hobby;
use App\User;
use App\Userhobby;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // onload get all user data
    public function index()
    {
        $userData = User::with('hobby')->get();
        $categories = Category::get();
        $hobby = Hobby::get();
        return view('welcome',compact('userData','categories','hobby'));
    }

    // save new data 
    public function addnewdata(Request $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->contact = $request->contact;
        $user->category = $request->category;
        
        $profilepic = time().'.'.$request->profilepic->extension();  
        $destinationPath = public_path()."/images/profile";
        $request->profilepic->move($destinationPath, $profilepic);
        $user->profilepic = $profilepic;
        $user->save();

        foreach ($request->hobby as $key => $value) {
            $hobby = new Userhobby();
            $hobby->user_id = $user->id;
            $hobby->hobby_id = $value;
            $hobby->save();
        }
        $userData = User::with('hobby')->get();
        $categories = Category::get();
        $hobby = Hobby::get();
        return response(['status' => true, 'statusCode' => 1, 'userData' => $userData,'categories'=>$categories,'hobby'=>$hobby]);
    }

    public function update(Request $request)
    {
        $user = User::find($request->id);
        $user->name = $request->name;
        $user->contact = $request->contact;
        $user->category = $request->category;
        $user->save();

        $userHobby = Userhobby::where('user_id',$request->id)->delete();
        foreach ($request->hobby as $key => $value) {
            $hobby = new Userhobby();
            $hobby->user_id = $request->id;
            $hobby->hobby_id = $value;
            $hobby->save();
        }
        $userData = User::with('hobby')->get();
        $categories = Category::get();
        $hobby = Hobby::get();
        return response(['status' => true, 'statusCode' => 1, 'userData' => $userData,'categories'=>$categories,'hobby'=>$hobby]);

    }

    // delete one record.
    public function delete(Request $request)
    {
        User::find($request->id)->delete();
        $userHobby = Userhobby::where('user_id',$request->id)->delete();
        $userData = User::with('hobby')->get();
        $categories = Category::get();
        $hobby = Hobby::get();
        return response(['status' => true, 'statusCode' => 1, 'userData' => $userData,'categories'=>$categories,'hobby'=>$hobby]);
    }

    // delete record in bulk.
    public function bulkdelete(Request $request)
    {

        User::whereIn('id',explode(",",$request->bulkDeleteArray))->delete();
        $userHobby = Userhobby::whereIn('user_id',explode(",",$request->bulkDeleteArray))->delete();
        $userData = User::with('hobby')->get();
        $categories = Category::get();
        $hobby = Hobby::get();
        return response(['status' => true, 'statusCode' => 1, 'userData' => $userData,'categories'=>$categories,'hobby'=>$hobby]);
    }

}
