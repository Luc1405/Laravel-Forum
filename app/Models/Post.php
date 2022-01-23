<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'image', 'type'
    ];


    public function user():BelongsToMany{
        return $this->belongsToMany(User::class);
        return $this->belongsToMany(User::class, 'post_user');
    }
}
