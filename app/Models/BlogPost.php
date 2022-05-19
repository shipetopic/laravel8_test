<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'content'];

    public function comments()
    {
        // return $this->hasMany('App\Comment');
        return $this->hasMany(Comment::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // function name matters
    public static function boot()
    {
        parent::boot();

        # Based on Events (not based on migration)
        static::deleting(function (BlogPost $blogPost){
            $blogPost->comments()->delete(); // deletes all comments - before deleting BlogPost
        });

        static::restoring(function (BlogPost $blogPost){
            $blogPost->comments()->restore();
        });
    }
}
