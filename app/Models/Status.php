<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;
    protected $guarded = [];
    public static function moduleStatusId($module, $status)
    {
        $record = Status::where('for_module', $module)
                        ->where('status', $status)
                        ->first();

        if (!$record) {
            throw new \Exception("Status not found for module: $module with status: $status");
        }

        return $record->id;
    }


}
