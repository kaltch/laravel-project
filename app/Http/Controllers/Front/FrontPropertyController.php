<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\City;
use App\Models\Listing;

use function PHPUnit\Framework\isEmpty;

class FrontPropertyController extends Controller
{
    public function listByCity(Request $req)
    {
        $city = new City();
        $listing = new Listing();

        $location = ''; // 城市名稱
        $cityId = 1;

        // 以城市名取得城市 ID
        if ($req->has('location')) {
            $location = strtolower($req->get('location'));
            $cityData = $city->getByName($location);
            $cityName = $cityData->name;

            if (!$cityId) {
                return response()->json(['error' => '找不到城市'], 404);
            }
        }
        // 測試
        $cityName = 'Taipei';
        // 取得城市的 airbnb 資料
        $list = $listing->getListByCityId($cityId);

        // 測試用: 假設使用者有加進清單，liked 屬性顯示 'Y'
        foreach ($list as $city) {
            $city->liked = 'Y';
        }

        // foreach ($list as $city) {
        //     echo $city->id;
        //     echo $city->name;
        //     echo $city->description;
        //     echo $city->listing_url;
        //     echo $city->picture_url;
        //     echo $city->latitude;
        //     echo $city->longitude;
        //     echo $city->city_id;
        //     echo $city->neighbourhood_cleansed;
        //     echo $city->property_type;
        //     echo $city->room_type;
        //     echo $city->host_id;
        //     echo $city->bathrooms_text;
        //     echo $city->bedrooms;
        //     echo $city->beds;
        //     echo $city->amenities;
        //     echo $city->accommodates;
        //     echo $city->price;
        //     echo $city->has_availability;
        //     echo $city->instant_bookable;
        //     echo $city->last_scraped;
        //     echo "<br>";
        // }
        // exit;

        if (isEmpty($location)) {
            return view('Front.data.cityGraph', ['list' => $list, 'cityName' => $cityName]);
        }
    }

    public function search(Request $req)
    {
        $city = new City();
        $listing = new Listing();

        $location = $req->query('location'); // datalist 資料
        $locationText = $req->query('location-text'); // input 
        $checkin = $req->query('checkin');
        $checkout = $req->query('checkout');
        $adults = $req->query('adults');
        // $children = $req->query('children', 0);  // 預設為 0
        // $pets = $req->query('pets', 0);  // 預設為 0
        if (isset($location)) {
            // 如果有 datalist id，直接以 id 搜尋
            $cityData = $city->getById($location);
            $cityName = $cityData->name;
        } else 
        {
            // 沒有 datalist id，以 locationText 逐個搜尋 (country, region, city, district)

        }

        // 已登入
        if (session()->has('member')) {
            $memberId = session()->get('memberId');
            // 取得城市的 airbnb 資料
            $list = $listing->getActiveListByCityId($cityData->id, $memberId);
        } else 
        {
            $list = $listing->getActiveListByCityId($cityData->id, null);
        }

        // 然後將結果傳回到 view
        if (session()->has('member')) {
            return view('Front.data.search', ['list' => $list, 'cityName' => $cityName]);

        } else
        {
            return view('Front.data.search', ['list' => $list, 'cityName' => $cityName]);
        }
    }

    public function searchByUrl(Request $req)
    {
        $city = new City();
        $listing = new Listing();

        $url = $req->query('url');
        $data = $listing->getByUrl($url);

        // 取得城市名
        $cityData = $city->getDetailById($data->city_id);
        $countrySName = $cityData->country_sname;
        $cityName = $cityData->name;

        if(isset($data))
        {
            return view('Front.data.detail', ['data' => $data, 'cityName' => $cityName, 'countrySName' => $countrySName]);
        }
        else
        {
            redirect('/error/404');
        }
    }

    public function getDetail(Request $req)
    {
        $city = new City();
        $listing = new Listing();

        $id = $req->id;
        $data = $listing->getById($id);

        // 取得城市名
        $cityData = $city->getDetailById($data->city_id);
        $countrySName = $cityData->country_sname;
        $cityName = $cityData->name;

        return view('Front.data.detail', ['data' => $data, 'cityName' => $cityName, 'countrySName' => $countrySName]);
    }
}
