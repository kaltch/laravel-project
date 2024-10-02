<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
</head>

<body>
    <div class="container">
        <header>
            <div class="d-flex justify-content-between align-items-center">
                <a href="/" id="Link">
                    <img src="/images/logo.png" alt="logo" style="max-width: 200px">
                </a>
                <a href="/">
                    <svg
                        preserveAspectRatio="none"
                        id="Vector_0"
                        class="pointer-events-none"
                        width="14"
                        height="14"
                        viewBox="0 0 14 14"
                        fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M1 13L13 1M1 1L13 13"
                            stroke="#1B272C"
                            stroke-width="1.5"
                            stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </a>
            </div>
        </header>
        <div class="row position-relative h-100">
            <div class="col">
                <img
                    class="bg-cover"
                    src="https://robbyn-sxuwti7d6j.figweb.site/cdn-cgi/imagedelivery/s-dfVpmPR-aKHmwFNwAgnQ/robbyn-sxuwti7d6j.figweb.site-b6fb20a5ec9ffd8dd54eae14463e68fd045cc0fc/public"
                    alt="main-image"
                    fetchpriority="high" />
            </div>
            <div class="col">
                <div class="d-flex flex-column space-y-2 mb-5">
                    <span class="fw-bold text-neutral-900 text-3xl">登入</span>
                    <span class="text-sm text-neutral-900">登入您的帳號</span>
                </div>
                <form id="loginForm" method="POST" action="/login" onsubmit="return doCheck()">
                    <?php echo e(csrf_field()); ?>

                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control form-input" id="fi-memberId" name="memberId" placeholder="example_username" value="<?php echo e(old('memberId')); ?>">
                        <label for="fi-memberId">帳號</label>
                        <div class="valid-feedback">

                        </div>
                        <div id="msg-username" class="invalid-feedback">
                            請輸入帳號
                        </div>
                    </div>

                    <div class="mb-3 form-floating">
                        <input type="password" class="form-control" id="fi-pwd" name="pwd" placeholder="Password">
                        <label for="fi-pwd">密碼</label>
                        <div class="valid-feedback">

                        </div>
                        <div id="msg-pwd" class="invalid-feedback">
                            請輸入密碼
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col">
                            <input type="text" class="form-control" id="fi-code" name="code" placeholder="請輸入驗證碼">
                        </div>
                        <div class="col float-end">
                            <img src="<?php echo e(captcha_src()); ?>" alt="captcha">
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <input type="checkbox" id="remember_me" />
                            <label for="remember_me" class="text-sm">記住我</label>
                        </div>
                        <div>
                            <a href="#" class="text-muted">
                                <span class="text-sm">忘記密碼 ?</span>
                            </a>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-danger">
                            <?php if($errors->has("error")): ?>
                            <?php echo e($errors->first("error")); ?>

                            <?php endif; ?>
                        </span>
                        <span class="text-sm text-danger">
                            <?php if($errors->has("code")): ?>
                            <?php echo e($errors->first("code")); ?>

                            <?php endif; ?>
                        </span>
                    </div>

                    <button id="btn-submit" class="btn btn-primary" type="submit">登入</button>
                </form>

                <!-- FOOTER -->
                <div class="position-absolute bottom-0 end-0">
                    <div class="text-muted">
                        <span>© Copyright 2024 - The Pier</span>
                        <span>·</span>
                        <a href="#">
                            <span>使用條款</span>
                        </a>
                        <span>·</span>
                        <a href="#">
                            <span>隱私權政策</span>
                        </a>
                    </div>

                </div>
                <!-- FOOTER -->
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="/js/jquery-3.7.1.min.js"></script>
    <script>
        var flag_username = false;
        var flag_pwd = false;
        var flag_code = false;
        var username;
        var pwd;
        var code;

        $(document).ready(function() {
            doCheck();

            // 監聽 帳號
            $("#fi-memberId").bind("input propertyChange", function() {
                if ($(this).val().length > 0) {
                    $("#fi-memberId").removeClass("is-invalid");
                    $("#fi-memberId").addClass("is-valid");
                    flag_username = true;
                } else {
                    $("#fi-memberId").removeClass("is-valid");
                    $("#fi-memberId").addClass("is-invalid");
                    flag_username = false;
                }
                doCheck();
            });

            // 監聽 密碼
            $("#fi-pwd").bind("input propertyChange", function() {
                if ($(this).val().length > 0) {
                    $("#fi-pwd").removeClass("is-invalid");
                    $("#fi-pwd").addClass("is-valid");
                    flag_pwd = true;
                } else {
                    $("#fi-pwd").removeClass("is-valid");
                    $("#fi-pwd").addClass("is-invalid");
                    flag_pwd = false;
                }
                doCheck();
            });

            // 監聽驗證碼
            $("#fi-code").bind("input propertyChange", function() {
                flag_code = ($(this).val().length > 0);
                doCheck();
            });

            function doCheck() {
                flag_username = ($("#fi-memberId").val().length > 0) ? true : false;

                if (flag_username == true && flag_pwd == true && flag_code == true) {
                    $('#btn-submit').attr('disabled', false);
                } else {
                    $('#btn-submit').attr('disabled', true);
                }
            }
        });
    </script>

</body>

</html><?php /**PATH /var/www/resources/views/Front/login.blade.php ENDPATH**/ ?>