<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
// use mysqli;

class TestForm extends Controller
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
    public function store(Request $request)
    {
        // $name = $request->only(['naam']);
        // $fileUploaded = $request->file('photo');
        // $request->file('photo')->move('images/','new.jpg');
        // return $name;
        $destURL = "";
        if ($request->hasFile('photo')) {
    //
    if ($request->file('photo')->isValid()) {
//retu
$fname = ($request->file('photo')->getClientOriginalName());
$request->file('photo')->move("uploads/",$fname);
$destURL = "uploads/".$fname;
}
}

      $input = $request->except(['submit']);
      $path = $request->path();
      $input['photo'] = htmlspecialchars($path).htmlspecialchars($destURL);
      // return response()->json($input);
      return response()->json($input,200,[],JSON_UNESCAPED_SLASHES);
        //
    }
    public function dbtest(){
      // $result =
      $data=[];
      $data['name']="Dhaniraam2";
      $data['email']="a@b.com";
      $msg = "ok";
      // $test = app('db')->insert('insert into user_details (name, email) values (?, ?)', ['Dhaniraam', 'Day2le@date.com']);
      try{
      $testID = app('db')->table('user_details')->insertGetId($data);
      $results = app('db')->select("SELECT * FROM user_details");
      return response()->json($testID);
    }
    catch(Exception $e){
      $msg = $e->getMessage();
      return $msg;
    }
    }

    //
}
