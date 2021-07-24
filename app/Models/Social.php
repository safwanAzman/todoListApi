<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    use HasFactory;

    protected $fillable = ["user_id","provider_name","provider_id"];
    protected $table = 'social_providers';
    protected $primaryKey = 'id';
}
