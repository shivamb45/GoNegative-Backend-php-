<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class UserProfile extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function test()
    {
      return "Hello Duniya";
    }


    public function createUser(Request $request)
    {
      $msg = "sab thik chal raha h";
      $res = [];
      $res['msg'] = $msg;
      $res['statusCode'] = '1';
      $res['statusString'] = 'ok';
      $naam = "";
      $email = "";
      //if name present then assign the variable the name else return a non-successfull response
      if($request->has('naam')){
        $naam = $request->input('naam');
      }
      else{
        //error
        $msg = "Name not Supplied";
        $res['msg'] = $msg;
        $res['statusCode'] = '0';
        $res['statusString'] = 'fail';
        $res['inputSupllied'] = $request->all();
        return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
      }

      //Now same procedure for e-mail
      if($request->has('email')){
        $email = $request->input('email');
      }
      else{
        //error
        $msg = "E-Mail not Supplied";
        $res['msg'] = $msg;
        $res['statusCode'] = '0';
        $res['statusString'] = 'fail';
        $res['inputSupllied'] = $request->all();
        return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
      }
      // all required parameters got. Now feed the user details in DB
      //TODO DBCOnnection
      $exist = app('db')->select('select count(email) from user_details where user_details.email = ?', [$email]);
      $exist = (array)json_decode(json_encode($exist[0]));
      if($exist['count(email)'] == '1'){
        $res['msg'] = "User Already Exist";
        $res['UserID'] = (array)app('db')->select('select userid from user_details where user_details.email = ?',[$email]);
        $res['UserID'] = (array)$res['UserID'][0];
        $res['UserID'] = $res['UserID']['userid'];
        return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
      }
      $insertData=[];
      $insertData['name'] = $naam;
      $insertData['email'] = $email;
      $res['exist'] = $exist['count(email)'];
      $res['UserID'] = app('db')->table('user_details')->insertGetId($insertData);
      return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
    }

    //Shivam Bharadwaj
}
