<?php

namespace App;

use App\User;
use App\Review;
use App\Business;
use App\Category;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Business extends Model
{
    protected $guarded = [];
    public $timestamps = true;

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function amOwner()
    {
        return $this->owner->is(auth()->user());
    }

    public function reviews()
    {
        return $this->hasMany(Review::class)->latest();
    }

    public function addReview($body, $rating, $userId = null)
    {
        return $this->reviews()
            ->create(
                [
                    'business_id' => $this->id,
                    'body' => $body,
                    'rating' => $rating,
                    'user_id' => $userId ? $userId : auth()->id()
                ]
            );
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function attachCategories($categories)
    {
        foreach ($categories as $categoryId) {
            $this->categories()->attach(['category_id' => $categoryId]);
        }
    }

    public function addCategory($categoryId)
    {
        return $this->categories()->attach(['category_id' => $categoryId]);
    }

    public function path()
    {
        return '/businesses/' . $this->slug;
    }

    public function image()
    {
        return  'storage/' . $this->front_image;
    }

    public function reviewedAlready()
    {
        return $this->reviews()->where(['user_id' => auth()->id()])->exists();
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }
}