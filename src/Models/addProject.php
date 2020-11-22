<?php

namespace Akrad\Bridage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addProject extends Model
{
    use HasFactory;

   // protected $guarded =[];
    protected $fillable = ['name' ,'viewer'];
}
