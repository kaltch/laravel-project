<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class Member extends Model
{
    public $timestamps = false;
    protected $table = "member";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "member_id",
        "name",
        "email",
        "password",
        "active",
        "created_at"
    ];
    public function getMember($name, $password)
    {
        $member = Member::where('name', $name)->first();
        if(!isset($member)) {
            return null;
        }
        if (!Hash::check($password, $member->password) )
        {
            return null;
        } else
        {
            return $member;
        }
    }

    public function getById($id)
    {
        $member = self::where("id", $id)->where("active", "Y")->first();
        return $member;
    }

    public function updatePassword($name, $newPassword)
    {
        $member = self::where("name", $name)->first();
        if(!isset($member)) {
            return false;
        }
        $member->password = bcrypt($newPassword);
        $member->save();
        return true;
    }
}
