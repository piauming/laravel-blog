<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Post;
use App\Models\ReadPost;
use App\Models\Like;
use App\Models\Follower;
use Illuminate\Support\Facades\DB;
use Response;

class APIController extends Controller
{
    public function followers($user_id) {

        $user = User::find($user_id);

        if ($user == NULL) {

            $error = array(
                'status' => 'error',
                'message' => 'resource not found'
            );
            return Response::json($error, 401);
        } 

        $followers = DB::table('followers')
        ->join('users', 'users.id', '=', 'followers.follower_id')
        ->where('followers.user_id', '=', $user_id)
        ->select('users.id', 'users.name', 'users.username', 'users.email')
        ->get();

        $result = array();
        $result["success"] = true;
        $result["followers"] = $followers;
        $result["count"] = count($followers);

        return $result;
    }

    public function posts() {
        $posts = Post::all();

        return $posts;
    }

    public function users() {
        $users = User::all();

        return $users;
    }

    public function likes() {
        $likes = Like::all();

        return $likes;
    }

    public function allFollowers() {
        $followers = Follower::all();

        return $followers;
    }



    public function readposts() {
        $posts = ReadPost::all();

        return $posts;
    }

    public function common($idsPath) {
        $ids = explode('/', $idsPath);

        $data = array();
        foreach ($ids as $id) {
            $followers = DB::table('followers')
            ->join('users', 'users.id', '=', 'followers.follower_id')
            ->where('followers.user_id', '=', $id)
            ->select('users.email')
            ->get()->pluck('email');

            array_push($data, $followers);   
        }

        $array = json_decode(json_encode($data), true);
        $friends = call_user_func_array('array_intersect', $array);

        $result = array();
        $result["success"] = true;
        $result["followers"] = $friends;
        $result["count"] = count($friends);

        return $result;
    }

    public function unread($userid) {
        /*
        SELECT p.body
        FROM posts p
        LEFT JOIN read_posts r ON p.id = r.post_id and r.user_id = 1
        WHERE r.post_id IS NULL and p.user_id != 1
        */

        $user = User::find($userid);

        if ($user == NULL) {

            $error = array(
                'status' => 'error',
                'message' => 'resource not found'
            );
            return Response::json($error, 401);
        } 

        $posts = DB::table('posts')
        ->leftJoin('read_posts', function($join) use ($userid) {
                        $join->on('posts.id', '=', 'read_posts.post_id');
                        $join->on('read_posts.user_id','=', DB::raw($userid));
                    })
        ->where('read_posts.post_id', '=', NULL)
        ->where('posts.user_id', '<>', DB::raw($userid))
        ->get()->pluck('body');

        $result = array();
        $result["success"] = true;
        $result["posts"] = $posts;
        $result["count"] = count($posts);

        return $result;
    }

    public function unreadFollower($userid, $followerid) {
        $user = User::find($userid);
        $follower = User::find($followerid);

        if ($user == NULL or $follower == NULL) {

            $error = array(
                'status' => 'error',
                'message' => 'resource not found'
            );
            return Response::json($error, 401);
        } 

        $posts = DB::table('posts')
        ->leftJoin('read_posts', function($join) use ($userid) {
                        $join->on('posts.id', '=', 'read_posts.post_id');
                        $join->on('read_posts.user_id','=', DB::raw($userid));
                    })
        ->where('read_posts.post_id', '=', NULL)
        ->where('posts.user_id', '<>', DB::raw($userid))
        ->where('posts.user_id', '=', DB::raw($followerid))
        ->get()->pluck('body');

        $result = array();
        $result["success"] = true;
        $result["posts"] = $posts;
        $result["count"] = count($posts);

        return $result;

    }
}
