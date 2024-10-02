@extends('Front.app')
@section('title', 'Error 404')
@section('content')
<div class='container'>
    <div class='row'>
        <div class='col fw-bold display-1 text-center d-flex flex-column justify-content-center ubuntu-regular'>
            <p style='color: #273238;'>NOT</p>
            <p style='color: #ff725e;'>OPEN YET</p>
            <a href="javascript:history.back()" class='text-base'><i class="fa-solid fa-arrow-left"></i> 回上一頁</a>
        </div>
        <div class='col vh-100' style='background-image: url("/images/error-400.svg"); '></div>
    </div>

</div>
@endsection