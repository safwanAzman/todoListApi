<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tasks extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","task_name","task_level","start_date","end_date","file_name"];
    protected $table = 'tasks';
    protected $primaryKey = 'id';

}
