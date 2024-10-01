<header class='container-fluid sticky-top bg-white py-3 border-bottom z-3' id='navbar'>
    <div class='container d-flex justify-content-between align-items-center'>
        <!-- Logo and Text -->
        <div class='d-flex align-items-center'>
            <a href="/">
                <img src='/images/logo.png' alt='The Pier Logo' width='160' class='me-2'>
                <!-- <div>
                <h1 class='h4 mb-0'>The Pier</h1>
                <p class='small text-muted mb-0'>Find your dream stay</p>
            </div> -->
            </a>
        </div>

        <!-- Navigation -->
        <nav class='d-none d-md-flex justify-content-between'>
            <a href='/about' class='nav-link me-3'>關於我們</a>
            <a href='/host' class='nav-link me-3'>屋主專區</a>
            <a href='/blog' class='nav-link me-3'>部落格</a>
            <a href='/advanced' class='nav-link text-danger fw-bold'>在 The Pier 瞧瞧進階功能 <i class="fa-solid fa-arrow-up-right-from-square"></i></a>
        </nav>

        <!-- Auth Buttons -->
        @if(session()->has('member'))
        <div class='d-flex align-items-center'>
            <a href='/member/wishlist' class='text-decoration-none text-black'>
                <div class='d-flex align-items-center me-5'>
                    <div class='rounded-circle me-2' style='background-image: url("/images/avatar-01.jpg"); background-size: cover; height: 50px; width: 50px;'></div>
                    {{ session()->get('member') }}
                </div>
            </a>

            <a href='/logout' class='nav-link'>登出</a>
        </div>
        @else
        <div class='d-flex align-items-center'>
            <a href='/login' class='nav-link me-3'>登入</a>
            <a href='/signup' class='btn btn-danger rounded-pill ms-2'>註冊</a>
        </div>
        @endif

    </div>
</header>