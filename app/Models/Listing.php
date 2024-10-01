<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    public $timestamps = false;
    protected $table = "listing";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "name",
        "description",
        "listing_url",
        "picture_url",
        "latitude",
        "longitude",
        "city_id",
        "neighbourhood_cleansed",
        "property_type",
        "room_type",
        "host_id",
        "bathrooms_text",
        "bedrooms",
        "beds",
        "amenities",
        "accommodates",
        "price",
        "has_availability",
        "instant_bookable",
        "last_scraped"
    ];
    public function getById($id)
    {
        $data = self::where("id", $id)
                    ->where("has_availability", 't')
                    ->first();
        return $data;
    }

    public function getByUrl($url)
    {
        $data = self::where("listing_url", $url)
                    ->where("has_availability", 't')
                    ->first();
        return $data;
    }
    
    public function getListByCityId($id)
    {
        $list = self::where("city_id", $id)->get();
        return $list;
    }

    public function getActiveListByCityId($id, $memberId)
    {
        if (isset($memberId)) {
            $list = self::where("city_id", $id)
                ->leftJoin("member_wishlist_item", 'listing.id', '=', 'member_wishlist_item.listing_id')
                // ->where("has_availability", "t")
                // ->where("instant_bookable", "t")
                // ->whereNotNull("price")
                ->where("created_by", $memberId)
                // ->where("active", "Y")
                ->orderBy("neighbourhood_cleansed")
                ->get();
        } else {
            $list = self::where("city_id", $id)
                ->where("has_availability", "t")
                ->where("instant_bookable", "t")
                ->whereNotNull("price")
                ->orderBy("neighbourhood_cleansed")->get();
        }
        return $list;
    }
}
