<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class PostDetails extends Controller
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

    public function createPost(Request $request){
      $msg = "sab thik chal raha h";
      $res = [];
      $res['msg'] = $msg;
      $res['statusCode'] = '1';
      $res['statusString'] = 'ok';
      $userid = "";
      $email = "";
      $story = "";
      $title = "";
      $imgurl = "";
      $title = "";
      //if title present then assign the variable the name else return a non-successfull response
      if($request->has('title')){
        $title = $request->input('title');
      }
      else{
        //error
        $msg = "Title not Supplied";
        $res['msg'] = $msg;
        $res['statusCode'] = '0';
        $res['statusString'] = 'fail';
        $res['inputSupllied'] = $request->all();
        return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
      }

      // check for story supplied or not
      if($request->has('story')){
        $story = $request->input('story');
      }
      else{
        //error
        $msg = "Story not Supplied";
        $res['msg'] = $msg;
        $res['statusCode'] = '0';
        $res['statusString'] = 'fail';
        $res['inputSupllied'] = $request->all();
        return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
      }

      //userID  ke liye check karo, uske corresponding email-id ke liye check karo
      if($request->has('userid')){
        $userid = $request->input('userid');
        $email = (array)app('db')->select('select email from user_details where userid = ?',[$userid]);
        if(empty($email)){
          $msg = "Invalid UserID";
          $res['msg'] = $msg;
          $res['statusCode'] = '0';
          $res['statusString'] = 'fail';
          $res['inputSupllied'] = $request->all();
          return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
        }
        $email = (array)$email[0];
        $email = $email['email'];

      }
      else{
        //error
        $msg = "userid not Supplied";
        $res['msg'] = $msg;
        $res['statusCode'] = '0';
        $res['statusString'] = 'fail';
        $res['inputSupllied'] = $request->all();
        return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
      }
      //Textual data Verified now move on to Image
      $countOfPosts = (array)app('db')->select('select count(distinct(postid)) from post_details');
      if(empty($countOfPosts)){
        $countOfPosts = '1';
      }
      else{
        $countOfPosts = (array)$countOfPosts[0];
        $countOfPosts = (int)$countOfPosts['count(distinct(postid))'];
        $countOfPosts = $countOfPosts + 1;
        $countOfPosts = (string)$countOfPosts;
      }

      $destURL = "";
      if ($request->hasFile('fimage')) {
        //
        if ($request->file('fimage')->isValid()) {
          //retu
          $fnameExt = ($request->file('fimage')->getClientOriginalExtension());
          $fname = $userid.$countOfPosts.".".$fnameExt;
          $request->file('fimage')->move("uploads/",$fname);
          $destURL = "uploads/".$fname;
        }
      }
      else {
        $msg = "Image not Supplied";
        $res['msg'] = $msg;
        $res['statusCode'] = '0';
        $res['statusString'] = 'fail';
        $res['inputSupllied'] = $request->all();
        return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
      }
      $actual_link = "http://$_SERVER[HTTP_HOST]"."/GoNegative"."/public";

      //everything set now feed the db;
      $insertData=[];
      $insertData['title'] = $title;
      $insertData['user_email'] = $email;
      $insertData['story'] = $story;
      $insertData['userid'] = $userid;
      $insertData['img_url'] = $actual_link."/".$destURL;
      // $insertData[]
      $insertData['likes'] = '0';
      $res['postid'] = app('db')->table('post_details')->insertGetId($insertData);
      $res['processedInput'] = $insertData;
      return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
    }

    public function getAllPosts($userid)
    {
      //considering there is atleast one post present
      $str="";
      $tmpst;
      $allPosts = app('db')->select('select * from post_details');
      foreach ($allPosts as $post) {
        # code...
        // $tmpst = (array)app('db')->select('select likes from post_reactions where postid = ? and userid = ?',[$post->postid,$userid]);
        // $tmpst = (array)json_decode(json_encode($tmpst[0]));
        //
        // $tmpst = (int)$tmpst['likes'];

        $likeStatus = (array)app('db')->select('select likes from post_reactions where userid = ? and postid = ?',[$userid,$post->postid]);
        if(empty($likeStatus)){
          $likeStatus = '0';
        }
        else{
          $likeStatus = (array)json_decode(json_encode($likeStatus[0]));
          // $likeStatus = $likeStatus[0];
          $likeStatus = (string)$likeStatus['likes'];
            // $likeStatus = (array)json_decode(json_encode($likeStatus[0]));
          // return $likeStatus;
        }
        $post->isLikedByCurrentUser = $likeStatus;
      }
      return response()->json($allPosts,200,[],JSON_UNESCAPED_SLASHES);
    }

    //Shivam Bharadwaj
}
