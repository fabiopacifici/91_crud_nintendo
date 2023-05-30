<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'platform', 'has_demo', 'price', 'description', 'release_date', 'image'];
    // disabilita completamente le fillable (WARNING!! occhio)
    //protected $guarded = [];
}
