<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get('/', function () use ($app) {
    return $app->version();
});
$app->get('hello-world',function () use ($app) {
  $str = "Hello World!";
  return $str;
});
$app->get('classtry', 'HelloWorld@test');
$app->post('formtry','TestForm@store');

$app->get('dbtry','TestForm@dbtest');
$app->post('createUser','UserProfile@createUser');
$app->post('createPost','PostDetails@createPost');
$app->get('togglelike/{userid}/{postid}','UserPostLikeLog@toggleLike');
$app->get('allPosts/{userid}','PostDetails@getAllPosts');
