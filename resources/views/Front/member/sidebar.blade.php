<nav id='sidebar' class='col-md-3 col-lg-2 d-sm-none d-md-block sidebar fixed-top vh-100 z-1 shadow-sm'>
        <div class='position-sticky pt-3 px-0 h-100'>
            <ul class='nav flex-column h-100 justify-content-between'>
                <div class=''>
                    <div class='fw-bold text-sm text-uppercase px-3 mb-3'>會員專區</div>
                    <li class='nav-item mb-3'>
                        <a class='nav-link{{ (request()->path() == "member/wishlist")?" active":"" }}' href='./wishlist'><i class='fa-regular fa-heart me-3'></i>收藏清單</a>
                    </li>
                    <li class='nav-item mb-3'>
                        <a class='nav-link{{ (request()->path() == "member/profile")?" active":"" }}' href='./profile'><i class='fa-solid fa-gear me-3'></i>帳戶設定</a>
                    </li>
                </div>
                <div class=''>
                    <li class='nav-item mb-3'>
                        <a class='nav-link' href='/logout'><i class='fa-solid fa-arrow-right-from-bracket me-3'></i>登出</a>
                    </li>
                </div>

            </ul>
        </div>
    </nav>