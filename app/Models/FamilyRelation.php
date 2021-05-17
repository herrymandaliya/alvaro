<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $table = "family_relation";

    protected $fillable = [
        'family_relation', 'family_name'
    ]; 
}
