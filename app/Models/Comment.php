<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class Comment extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['user_id', 'content'];

    // blogPost  -->  blog_post_id
    /*
    public function blogPost()
    {
        // return $this->belongsTo('App\BlogPost', 'post_id', 'blog_post_id');
        // return $this->belongsTo('App\BlogPost');
        return $this->belongsTo(BlogPost::class);
    }
    */

    public function commentable()
    {
        return $this->morphTo();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new LatestScope);

        static::creating(function (Comment $comment){
            if ($comment->commentable_type === BlogPost::class){
                Cache::tags(['blog-post'])->forget("blog-post-{$comment->commentable_id}");
                Cache::tags(['blog-post'])->forget("mostCommented");
            }            
        });  
    }
}
