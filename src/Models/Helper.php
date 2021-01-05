<?php

namespace Akrad\Bridage\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Helper extends Model
{
    //use HasFactory;

   // protected $guarded =[];
    protected $fillable = ['events' ,'object','task_name','group_name','user','statuse'];
}
