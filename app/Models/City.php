<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;
    protected $table = "city";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "name",
        "short_name",
        "region_id",
        "active",
        "created_at"
    ];
    public function getById($id)
    {
        // select 
        // a.id as city_id, a.name as city_name,
        // b.id as region_id, b.name as region_name,
        // c.id as country_id, c.name as country_name 
        // from city a
        // join region b on a.region_id = b.id
        // join country c on b.id = c.id;

        $data = self::join("region", "city.region_id", "=", "region.id")
            ->join("country", "region.country_id", "=", "country.id")
            ->select("city.id", "city.name", "city.short_name", "country.name as country_name", "region.name as region_name", "country.short_name as country_sname", "region.short_name as region_sname")
            ->where("city.id", $id)
            ->where("city.active", 'Y')
            ->first();
        return $data;
    }

    // join 資料表 country, region，同時取得 country.name, region.name
    public function getDetailById($id)
    {
        // select 
        // a.id as city_id, a.name as city_name,
        // b.id as region_id, b.name as region_name,
        // c.id as country_id, c.name as country_name 
        // from city a
        // join region b on a.region_id = b.id
        // join country c on b.id = c.id;

        $data = self::join("region", "city.region_id", "=", "region.id")
            ->join("country", "region.country_id", "=", "country.id")
            ->select("city.id", "city.name", "city.short_name", "country.name as country_name", "region.name as region_name", "country.short_name as country_sname", "region.short_name as region_sname")
            ->where("city.id", $id)
            ->where("city.active", 'Y')
            ->first();
        return $data;
    }

    public function getByName($name)
    {
        $data = self::where("name", $name)->where("active", 'Y')->first();
        return $data;
    }
}
