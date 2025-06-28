<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Distributor extends Model
{
    protected $table = 'distributors';

    protected $primaryKey = 'distributor_id';

    public $timestamps = false;

    protected $fillable = ['name'];
}
