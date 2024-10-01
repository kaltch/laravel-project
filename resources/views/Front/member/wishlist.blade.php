@extends('front.app')
@section('title', 'Member wishlist')
@section('content')
<div class='row w-100'>
    <!-- SideBar -->
    @include("Front.member.sidebar")
    <!-- Content -->
    <main class='col-md-9 ms-sm-auto col-lg-10 px-md-4'>
        <div class='container'>
            <div class='mb-3'>
                <div class='fw-bold text-lg'>收藏清單 <span class='text-muted text-sm'>Member wishlist</span></div>
            </div>
            <div class='card'>
                <ul class='list-group list-group-flush'>
                    @foreach ($list as $data)
                    @if ($data->available == 't')
                    <li class='list-group-item' data-id='{{ $data->listing_id }}'>
                        <div class='d-flex justify-content-between align-items-center my-3 px-3'>
                            <div class='row flex-grow-1'>
                                <div class='col-md-2'>
                                    <div class='img-fluid img-thumbnail bg-cover rounded ratio ratio-1x1 list-item-img-sm' style='background-image: url("{{ $data->picture_url }}");'></div>
                                </div>
                                <div class='col-md-10 d-flex flex-column justify-content-around text-break'>
                                    <div class='row'>
                                        <div class='col-10'>
                                            <div class='text-sm text-muted'>位於 {{ $data->neighbourhood_cleansed }} 的 {{ $data->property_type }}</div>
                                        </div>
                                        <div class='col-2'>
                                        </div>
                                    </div>
                                    <div class='fw-bold text-neutral-900 text-base'>
                                        <a href='/properties/detail/{{ $data->listing_id }}' target='_blank' class='text-decoration-underline' style='text-decoration: none; color: inherit;'>
                                            {{ $data->name }}
                                        </a>
                                    </div>

                                </div>
                            </div>
                            <div class='flex-shrink-0'>
                                <a href='javascript:void(0);' class='btn btn-light' onclick='deleteItem("{{ $data->id }}")'>移除</a>
                            </div>
                        </div>
                    </li>
                    @else
                    <li class='list-group-item' data-id='{{ $data->listing_id }}'>
                        <div class='d-flex justify-content-between my-3 px-3'>
                            <div class='row flex-grow-1'>
                                <div class='col-md-2'>
                                    <div class='img-fluid img-thumbnail bg-cover rounded ratio ratio-1x1 list-item-img-sm' style='background-image: url("/images/error-01.jpg");'></div>
                                </div>
                                <div class='col-md-10 d-flex flex-column justify-content-around text-break'>
                                    <div class='fw-bold text-muted text-base'>連結已失效</div>
                                </div>
                            </div>
                            <div class='flex-shrink-0'>
                                <a href='javascript:void(0);' class='btn btn-light' onclick='deleteItem("{{ $data->id }}")'>移除</a>
                            </div>
                        </div>
                    </li>
                    @endif

                    @endforeach
                </ul>
            </div>
        </div>

    </main>
</div>

<script>
    const navbar = $('#navbar');
    const sidebar = $('#sidebar');
    sidebar.attr('style', `padding-top: ${navbar.outerHeight(true)}px`); // 設定側邊欄的 top 距離

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // 刪除清單資料
    function deleteItem(dataId) {
        if (dataId.length > 0) {
            $.ajax({
                url: '/member/wishlist/delete',
                type: 'post',
                data: {
                    id: dataId
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('刪除失敗');
                    }
                },
                error: function(error) {
                    alert('Error - /member/wishlist/delete');
                    // console.log('Error: ' + error.responseText);
                }
            });
        }
    }
</script>

@endsection