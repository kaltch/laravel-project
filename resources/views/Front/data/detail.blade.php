@extends('front.app')
@section('title', 'Explore')
@section('content')
<style>
    .listing-title {
        font-size: 24px;
        font-weight: bold;
    }

    .rating {
        color: #ff5a5f;
    }

    .price-box {
        border: 1px solid #ddd;
        padding: 20px;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .property-details {
        margin-top: 20px;
    }

    .main-image img {
        width: 100%;
        height: auto;
    }

    .gallery img {
        height: 100px;
        object-fit: cover;
        width: 100%;
    }

    .gallery .col {
        padding-right: 5px;
    }

    .gallery .see-all {
        position: absolute;
        top: 0;
        right: 0;
        background-color: rgba(0, 0, 0, 0.5);
        padding: 10px;
        color: white;
    }
</style>

<div class='container mt-4'>
    <div class='row'>
        <!-- Main Section -->
        <div class='col-lg-8'>
            <!-- Location 城市 -->
            <p class='text-muted'><a href='/properties/search?location=1&checkin=&checkout=&adults=2'>{{ $cityName }}, {{ $countrySName }}</a></p>

            <!-- Title and Rating -->
            <div>
                <h1 class='fw-bold text-2xl'>{{ $data->name }}</h1>
                <div class='rating'>
                    <span>★★★★★</span> <span>(1 review)</span>
                </div>
            </div>

            <!-- Image Gallery -->
            @php
            if (!isset($data->picture_url)) {
            $img = 'https://via.placeholder.com/800x400';
            } else
            {
            $img = $data->picture_url;
            }
            @endphp
            <div class='row mt-3'>
                <div class='col-12 main-image vh-100 bg-cover bg-position-center'
                    style='background-image: url("{{ $img }}"); max-height: 400px;'>
                </div>
            </div>
            <!-- <div class='row mt-2 gallery'>
                <div class='col-3'>
                    <img src='https://via.placeholder.com/150' alt='Gallery image'>
                </div>
                <div class='col-3'>
                    <img src='https://via.placeholder.com/150' alt='Gallery image'>
                </div>
                <div class='col-3'>
                    <img src='https://via.placeholder.com/150' alt='Gallery image'>
                </div>
                <div class='col-3 position-relative'>
                    <img src='https://via.placeholder.com/150' alt='Gallery image'>
                    <div class='see-all'>See All</div>
                </div>
            </div> -->

            <!-- Property Details -->
            <div class='row mt-4 text-center'>
                <div class='col'>
                    <p><strong>Type</strong></p>
                    <p>{{ $data->room_type }}</p>
                </div>
                <div class='col'>
                    <p><strong>Rooms</strong></p>
                    <p>{{ $data->accommodates }} Guests</p>
                </div>
                <div class='col'>
                    <p><strong>Bathrooms</strong></p>
                    <p>{{ $data->bathrooms_text }}</p>
                </div>
            </div>

            <!-- About Section -->
            <div class='mt-4'>
                <h3 class="text-xl">房源介紹</h3>
                <div class='overflow-y-hidden' id='sec-of01' style='max-height: 6rem;'>{!! $data->description !!}</div>
                <a href='javascript:void(0)' onclick='show("sec-of01")' id='link-show-sec-of01'>顯示更多</a>
            </div>

            <!-- Amenities Section -->
            <div class='amenities mt-4'>
                <h3 class='text-xl'>房源設備</h3>
                <ul class='ps-0 overflow-y-hidden' id='sec-of02' style='max-height: 110px;'>
                    @php
                    $amenitiesArr = json_decode($data->amenities, true);
                    @endphp
                    @foreach ($amenitiesArr as $amenity)
                    <li>{{ $amenity }}</li>
                    @endforeach
                </ul>
                <a href='javascript:void(0)' onclick='show("sec-of02")' id='link-show-sec-of02'>顯示更多</a>
            </div>
        </div>


        <!-- Sidebar 價格 -->
        <div class='col-lg-4 mt-lg-5 border-bottom'>
            <!-- Wishlist Form -->
            <div class='mb-4 d-flex justify-content-end {{ (session()->get("member"))?"":"invisible" }}'>
                <button class='btn btn-primary'>
                    <i class='fa-solid fa-heart mr-2'></i>
                    加入收藏
                </button>
            </div>

            <div class='price-box text-center'>
                <p class='font-weight-bold' style='font-size:24px;'>${{ number_format($data->price, 0) }} / 平均每晚</p>
                <!-- Price Comparison Form -->
                <div>
                    <!-- 在這邊上面加上模糊 div，並加上文字 "功能尚未解鎖" -->
                    <div class='d-flex justify-content-center align-items-center' style='min-height: 200px'>
                        <span class="text-lg">
                            <i class='fa-solid fa-lock mr-5'></i>
                            功能未解鎖
                        </span>
                    </div>
                    <!-- <div class='mb-3'>
                        <label for='checkIn' class='form-label'>Check In</label>
                        <input type='date' class='form-control' id='checkIn' disabled>
                    </div>
                    <div class='mb-3'>
                        <label for='checkOut' class='form-label'>Check Out</label>
                        <input type='date' class='form-control' id='checkOut' disabled>
                    </div>

                    <button class='btn btn-danger w-100' disabled>Compare Prices</button>

                    <p class='text-muted mt-3'>Load and compare the price across multiple sites</p> -->
                </div>

            </div>
        </div>

    </div>
</div>
<script>
    function show(id) {
        $('#' + id).removeClass('overflow-y-hidden');
        $('#' + id).attr('style', '');
        $('#link-show-' + id).hide();
    }
</script>
@endsection