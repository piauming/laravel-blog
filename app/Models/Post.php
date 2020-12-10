<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
use App\Models\ReadPost;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'body',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function likedBy(User $user) {
        return $this->likes->contains('user_id', $user->id);
    }

    // moved to policy
    // public function ownedBy(User $user) {
    //     return $user->id === $this->user_id;
    // }

    public function isCreator(User $user) {
        $reader_id = $user->id;
        return ($reader_id === $this->user->id );
    }

    public function likes() {
        return $this->hasMany(Like::class);
    }

    public function readBy(User $user) {
        return $this->reads->contains('user_id', $user->id);
    }

    public function reads() {
        return $this->hasMany(ReadPost::class);
    }
}
 