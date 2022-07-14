<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function getRouteKeyName() {
        return 'league_external_id';
    }
}
