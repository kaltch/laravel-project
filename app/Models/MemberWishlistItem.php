<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class MemberWishlistItem extends Model
{
    public $timestamps = false;
    protected $table = "member_wishlist_item";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "listing_id",
        "active",
        "created_by",
        "created_at",
        "updated_at"
    ];
    public function getListByMemberId($memberId)
    {
        $list = self::where('created_by', $memberId)
            ->where('active', 'Y')
            ->leftJoin('listing', 'member_wishlist_item.listing_id', '=', 'listing.id')
            ->select(
                'member_wishlist_item.listing_id',
                'member_wishlist_item.created_by',
                DB::raw("CASE WHEN listing.instant_bookable = 't' THEN 't' ELSE listing.instant_bookable END AS available,
                member_wishlist_item.id, listing.neighbourhood_cleansed, listing.`name`, listing.listing_url, listing.picture_url
                ")
            )
            ->get();
        return $list;
    }
}
