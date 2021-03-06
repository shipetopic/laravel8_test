<?php

namespace App\Models;

use App\Scopes\DeletedAdminScope;
use App\Scopes\LatestScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;

class BlogPost extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $fillable = ['title', 'content', 'user_id'];

    public function comments()
    {
        // return $this->hasMany('App\Comment');

        // return $this->hasMany(Comment::class);

        return $this->morphMany(Comment::class, 'commentable')->latest();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function tags(){
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function image()
    {
        return $this->morphOne(Image::class, 'imageable');
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

    public function scopeLatestWithRelations(Builder $query)
    {
        return $query
            ->latest()
            ->withCount('comments')
            ->with('user')
            ->with('tags');
    }
    
    // function name matters
    public static function boot()
    {
        # Adding before 'parent::boot()' so it takes precedent
        static::addGlobalScope(new DeletedAdminScope);

        parent::boot();

        // static::addGlobalScope(new LatestScope);
        

        # Based on Events (not based on migration)
        static::deleting(function (BlogPost $blogPost){
            $blogPost->comments()->delete(); // deletes all comments - before deleting BlogPost
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });

        static::updating(function (BlogPost $blogPost){
            Cache::tags(['blog-post'])->forget("blog-post-{$blogPost->id}");
        });        

        static::restoring(function (BlogPost $blogPost){
            $blogPost->comments()->restore();
        });
    }
}
