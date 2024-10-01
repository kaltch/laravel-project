<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/app.css">
    <!-- toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
</head>

<body>
    @if(Session::has("message"))
    <script>
        Toastify({
            text: "{{ Session::get('message') }}",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "left",
            stopOnFocus: false,
            // style: {
            //     background: "linear-gradient(to right, #00b09b, #96c93d)",
            // },
        }).showToast();
    </script>
    @endif
    
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
                    src="https://robbyn-sxuwti7d6j.figweb.site/cdn-cgi/imagedelivery/s-dfVpmPR-aKHmwFNwAgnQ/robbyn-sxuwti7d6j.figweb.site-c0fb41030e40d0565730174362a3f8d11f078f0b/public"
                    alt="main-image"
                    fetchpriority="high" />
            </div>
            <div class="col">
                <div class="d-flex flex-column space-y-2 mb-8">
                    <span class="fw-bold text-neutral-900 text-3xl">註冊</span>
                    <span class="text-sm text-neutral-900">註冊您的帳號</span>
                </div>
                <form method="POST" action="/signup" id="f-signup">
                    {{ csrf_field() }}
                    <div class="mb-3 form-floating">
                        <input type="email" class="form-control" id="fi-email" name="email" placeholder="name@example.com">
                        <label for="fi-email">Email</label>
                        <div class="valid-feedback">
                            格式正確
                        </div>
                        <div id="msg-email" class="invalid-feedback"
                            data-msg01="email 格式錯誤 (長度需大於 2 字, 並包含 @)"
                            data-msg02="email 已被註冊">
                            請輸入 email
                        </div>
                    </div>

                    <div class="mb-3 form-floating">
                        <input type="text" class="form-control" id="fi-memberId" name="memberId" placeholder="username">
                        <label for="fi-memberId">帳號</label>
                        <!-- <div class="valid-feedback">
                            格式正確
                        </div> -->
                        <div id="msg-username" class="invalid-feedback"
                            data-msg01="帳號格式錯誤 (長度需介於 3-10 字)">
                            請輸入帳號
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col form-floating">
                            <input type="password" class="form-control" id="fi-pwd" name="pwd" placeholder="Password">
                            <label for="fi-pwd">密碼</label>
                            <!-- <div class="valid-feedback">
                                格式正確
                            </div> -->
                            <div id="msg-pwd" class="invalid-feedback"
                                data-msg01="密碼格式錯誤 (長度需介於 3-12 字)">
                                請輸入密碼
                            </div>
                        </div>

                        <div class="col form-floating">
                            <input type="password" class="form-control" id="fi-pwd-chk" placeholder="Password">
                            <label for="fi-pwd-chk">確認密碼</label>
                            <div class="valid-feedback">
                                密碼一致
                            </div>
                            <div id="msg-pwd-chk" class="invalid-feedback">
                                密碼不一致
                            </div>
                        </div>
                    </div>
                    <div>
                        <span class="text-sm text-danger">
                            @if ($errors->has("error"))
                            {{ $errors->first("error") }}
                            @endif
                        </span>
                    </div>
                    <div class="row d-flex justify-content-center">
                        <button class="col btn btn-primary" type="button" id="btn-submit" onclick="confirm('是否確認送出?') ? sendData() : ''">註冊</button>
                    </div>
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
    <!-- toastify -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        var flag_email = false;
        var flag_username = false;
        var flag_pwd = false;
        var flag_pwd_chk = false;
        var chk_value = [];
        var dataJSON = {};

        var op_message = "";

        // 監聽 email
        $("#fi-email").bind("input propertyChange", function() {
            const reg = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            if ($(this).val().length > 2 && (reg.test($(this).val()))) {
                const dataJSON = {};
                dataJSON.email = $("#fi-email").val();
                console.log(dataJSON);

                showdata_checkuni({
                    "status": "success"
                });

                // $.ajax({
                //     type: "POST",
                //     url: "/api/account/signup/check",
                //     data: {
                //         "email": dataJSON.email,
                //         "_token": "{{ csrf_token() }}"
                //     },
                //     dataType: "json",
                //     success: showdata_checkuni,
                //     error: function() {
                //         alert("error- /account/signup/check");
                //     }
                // })
            } else {
                $("#fi-email").removeClass("is-valid");
                $("#fi-email").addClass("is-invalid");

                op_message = $("#msg-email").data("msg01");
                $("#msg-email").text(op_message);
                flag_email = false;
            }
        });

        // 監聽 帳號
        $("#fi-memberId").bind("input propertyChange", function() {

            if ($(this).val().length >= 3 && $(this).val().length <= 10) {
                $("#fi-memberId").removeClass("is-invalid");
                $("#fi-memberId").addClass("is-valid");
                flag_username = true;
            } else {
                $("#fi-memberId").removeClass("is-valid");
                $("#fi-memberId").addClass("is-invalid");

                op_message = $("#msg-username").data("msg01");
                $("#msg-username").text(op_message);
                flag_username = false;
            }
        });

        // 監聽 密碼
        $("#fi-pwd").bind("input propertyChange", function() {
            // 檢查密碼一致
            checkPwd();

            if ($(this).val().length >= 3 && $(this).val().length <= 12) {
                $("#fi-pwd").removeClass("is-invalid");
                $("#fi-pwd").addClass("is-valid");
                flag_pwd = true;
            } else {
                $("#fi-pwd").removeClass("is-valid");
                $("#fi-pwd").addClass("is-invalid");

                op_message = $("#msg-pwd").data("msg01");
                $("#msg-pwd").text(op_message);
                flag_pwd = false;
            }
        });

        // 監聽 確認密碼
        $("#fi-pwd-chk").bind("input propertyChange", checkPwd);

        function showdata_checkuni(data) {
            // console.log(data);
            if (data.status == "success") {
                $("#fi-email").removeClass("is-invalid");
                $("#fi-email").addClass("is-valid");
                flag_email = true;
            } else {
                $("#fi-email").removeClass("is-valid");
                $("#fi-email").addClass("is-invalid");

                if (data.message == "email 已被註冊") {
                    op_message = $("#msg-email").data("msg02");
                    $("#msg-email").text(op_message);
                }
                flag_email = false;
            }
        }

        function checkPwd() {
            if ($("#fi-pwd-chk").val() == $("#fi-pwd").val()) {
                $("#fi-pwd-chk").removeClass("is-invalid");
                $("#fi-pwd-chk").addClass("is-valid");
                flag_pwd_chk = true;
            } else {
                $("#fi-pwd-chk").removeClass("is-valid");
                $("#fi-pwd-chk").addClass("is-invalid");
                flag_pwd_chk = false;
            }
        }

        function sendData() {
            $("form").submit();
        };

        function show_msg(data) {
            console.log(data);
            alert("註冊成功");

            // 導向首頁
            window.location.replace("/");
        }
    </script>
</body>

</html>