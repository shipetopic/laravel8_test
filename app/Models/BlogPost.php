<?php

namespace App\Models;

use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        // return $this->hasMany('App\Comment');

        // return $this->hasMany(Comment::class);

        return $this->hasMany(Comment::class)->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLatest(Builder $query)
    {
        return $query->orderBy(static::CREATED_AT, 'desc');
    }

    public function scopeMostCommented(Builder $query)
    {
        // field: 'comments_count'
        return $query->withCount('comments')->orderBy('comments_count', 'desc');
    }
    
    // function name matters
    public static function boot()
    {
        parent::boot();

        // static::addGlobalScope(new LatestScope);

        # Based on Events (not based on migration)
        static::deleting(function (BlogPost $blogPost){
            $blogPost->comments()->delete(); // deletes all comments - before deleting BlogPost
        });

        static::restoring(function (BlogPost $blogPost){
            $blogPost->comments()->restore();
        });
    }
}
