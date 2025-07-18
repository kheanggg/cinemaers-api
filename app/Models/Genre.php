<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    protected $table = 'genres';

    protected $primaryKey = 'genre_id';

    public $timestamps = false;

    protected $fillable = ['name'];
}
