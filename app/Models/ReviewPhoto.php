<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class ReviewPhoto extends Model
{
    protected $fillable = ['review_id', 'path'];

    protected function url(): Attribute
    {
        return Attribute::get(fn () => asset('storage/' . $this->path));
    }

    public function review()
    {
        return $this->belongsTo(Review::class);
    }
}
