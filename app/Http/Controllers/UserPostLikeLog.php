<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;


class UserPostLikeLog extends Controller
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
    public function toggleLike($userid,$postid){
      $res= [];
      $totalLikesinPost = app('db')->select('select likes from post_details where postid = ?',[$postid]);
      $totalLikesinPost = (array)json_decode(json_encode($totalLikesinPost[0]));
      $totalLikesinPost = (int)$totalLikesinPost['likes'];
      $likeStatus = (array)app('db')->select('select likes from post_reactions where userid = ? and postid = ?',[$userid,$postid]);
      if(empty($likeStatus)){
        app('db')->insert('insert into post_reactions (postid,userid,likes) values (?,?,?)',[$postid,$userid,0]);
        $likeStatus = '0';
        $res['msg'] = "Not liked already. Ab Change krDiya";
      }
      else{
        $likeStatus = (array)json_decode(json_encode($likeStatus[0]));
        // $likeStatus = $likeStatus[0];
        $likeStatus = (int)$likeStatus['likes'];
        $res['msg'] = 'Like Status as Already Present in DB is - '.$likeStatus;
        // $likeStatus = (array)json_decode(json_encode($likeStatus[0]));
        // return $likeStatus;
      }
      $likeStatus = (int)$likeStatus;
      if($likeStatus==0){
        $likeStatus = 1;
        $totalLikesinPost = $totalLikesinPost + 1;

      }
      else{
        $likeStatus = 0;
        $totalLikesinPost = $totalLikesinPost - 1;
      }
      app('db')->update('update post_details set likes = ? where postid = ?',[$totalLikesinPost,$postid]);
      $res['updatedLikeStatus'] = $likeStatus;
      app('db')->update('update post_reactions set likes = ? where userid = ? and postid = ?', [$likeStatus,$userid,$postid]);
      $res['statusCode'] = '1';
      $res['statusString'] = 'ok';
      return response()->json($res,200,[],JSON_UNESCAPED_SLASHES);
    }


    //Shivam Bharadwaj
}
