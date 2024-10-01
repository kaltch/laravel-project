@extends('front.app')
@section('title', 'Member wishlist')
@section('content')
<div class='row w-100'>
    <!-- SideBar -->
    @include("Front.member.sidebar")
    <!-- Content -->
    <main class='col-md-9 ms-sm-auto mt-4 col-lg-10 px-md-4'>
        <div class='container'>
            <div class='card'>
                <ul class='list-group list-group-flush'>
                    <li class='list-group-item' data-id='{{ $data->listing_id }}'>
                        <div class='d-flex flex-column justify-content-between align-items-center my-3 px-md-5'>
                            <!-- section01 帳戶資料 -->
                            <div class='mx-5 my-3 d-flex flex-column w-100 d-none'>
                                <h2 class='fw-bold text-lg'>帳戶資料</h2>
                                <div class='mb-3'><span class='text-muted text-sm'>你可以在這修改你的 Email 與 帳號</span></div>
                                <div class='card'>
                                    <form action='/member/profile/account/update' method='post' id='form01'>
                                        {{ csrf_field() }}
                                        <div class='mx-5 my-4'>
                                            <div class='mb-3'>
                                                <div class="d-flex align-items-baseline">
                                                    <label for='form01-1' class='form-label fw-bold me-3'>帳號</label>
                                                    <span class='text-sm text-danger invisible' id='form01-1-msg'>帳號格式錯誤</span>
                                                </div>
                                                <input type='text' class='form-control ' id='form01-1' placeholder='username' value='{{ $data->name }}'>
                                            </div>
                                            <div class='mb-3'>
                                                <div class="d-flex align-items-baseline">
                                                    <label for='form01-2' class='form-label fw-bold me-3'>Email</label>
                                                    <span class='text-sm text-danger invisible' id='form01-2-msg'>Email 格式錯誤</span>
                                                </div>
                                                <input type='email' class='form-control' id='form01-2' placeholder='name@example.com' value='{{ $data->email }}'>
                                            </div>
                                            <div class='text-end'>
                                                <input type='submit' class='btn btn-primary' onclick='sendData("form01")' value='提交'>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- section02 修改密碼 -->
                            <div class='mx-5 my-3 d-flex flex-column w-100'>
                                <h2 class='fw-bold text-lg'>修改密碼</h2>
                                <div class='mb-3'><span class='text-muted text-sm'>你可以在這修改你的密碼</span></div>
                                <div class='card'>
                                    <form action='/member/profile/password/update' method='post' id='form02'>
                                        {{ csrf_field() }}
                                        <div class='mx-5 my-4'>
                                            <div class="d-flex align-items-baseline">
                                                <label for='form02-1' class='form-label fw-bold me-3'>原密碼</label>
                                                <span class='text-sm text-danger invisible' id='form02-1-msg'>密碼格式錯誤</span>
                                            </div>
                                            <div class='input-group mb-3'>
                                                <input type='password' class='form-control' id='form02-1' name='password' placeholder='password' value='' aria-label='password' aria-describedby='addon-form02-1'>
                                                <span class='input-group-text text-center' id='addon-form02-1' onclick='toggleShowPwd("form02-1")' style='cursor: pointer; width: 3rem;'><i class="fa-solid fa-eye-slash"></i></span>
                                            </div>
                                            <div class="d-flex align-items-baseline">
                                                <label for='form02-2' class='form-label fw-bold me-3'>新密碼</label>
                                                <span class='text-sm text-danger invisible' id='form02-2-msg'
                                                      data-msg01='密碼格式錯誤'
                                                      data-msg02='新舊密碼需不同'
                                                >
                                                密碼格式錯誤
                                                </span>
                                            </div>
                                            <div class='input-group mb-3'>
                                                <input type='password' class='form-control' id='form02-2' name='newPassword' placeholder='new password' value='' aria-label='password' aria-describedby='addon-form02-2'>
                                                <span class='input-group-text text-center' id='addon-form02-2' onclick='toggleShowPwd("form02-2")' style='cursor: pointer; width: 3rem;'><i class="fa-solid fa-eye-slash"></i></span>
                                            </div>
                                            <div class='d-flex justify-content-between'>
                                                <div class='text-danger'>
                                                @if ($errors->has("errorF02"))
                                                {{ $errors->first("errorF02") }}
                                                @endif
                                                </div>
                                                <input type='submit' class='btn btn-primary' onclick='sendData("form02")' value='提交'>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

    </main>
</div>

<script>
    const navbar = $('#navbar');
    const sidebar = $('#sidebar');
    sidebar.attr('style', `padding-top: ${navbar.outerHeight(true)}px`); // 設定側邊欄的 top 距離

    // 顯示/隱藏密碼
    function toggleShowPwd(inputId) {
        const pwdInput = document.getElementById(inputId);
        if (pwdInput.type === 'password') {
            pwdInput.type = 'text';
        } else {
            pwdInput.type = 'password';
        }
        // get child elements
        const eyeIcon = pwdInput.nextElementSibling.firstChild;
        console.dir(eyeIcon);
        if (eyeIcon.classList.contains('fa-eye-slash')) {
            eyeIcon.classList.remove('fa-eye-slash');
            eyeIcon.classList.add('fa-eye');
        } else {
            eyeIcon.classList.remove('fa-eye');
            eyeIcon.classList.add('fa-eye-slash');
        }
    }

    var flag_username = false;
    var flag_email = false;
    var flag_pwd = false;
    var flag_pwd_new = false;
    var flag_pwd_chk = false;
    var emalReg = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    function sendData(formId) {
        event.preventDefault();
        let form = $('#' + formId);

        if (formId == 'form01') {
            flag_username = ($('#form01-1').val().length >= 3 && $('#form01-1').val().length <= 10);
            flag_email = emalReg.test($('#form01-2').val());
            if (flag_username == false) {
                $('#' + formId + '-1-msg').addClass('visible');
                $('#' + formId + '-1-msg').removeClass('invisible')

            } else {
                $('#' + formId + '-1-msg').addClass('invisible');
                $('#' + formId + '-1-msg').removeClass('visible');
            }
            if (flag_email == false) {
                $('#' + formId + '-2-msg').addClass('visible');
                $('#' + formId + '-2-msg').removeClass('invisible');
            } else {
                $('#' + formId + '-2-msg').addClass('invisible');
                $('#' + formId + '-2-msg').removeClass('visible');
            }
            // if (flag_username && flag_email) form.submit();
        } else if (formId == 'form02') {
            flag_pwd = ($('#form02-1').val().length >= 3 && $('#form02-1').val().length <= 12);
            flag_pwd_new = ($('#form02-1').val().length >= 3 && $('#form02-1').val().length <= 12);
            flag_pwd_chk = ($('#form02-1').val() == $('#form02-2').val());
            
            if (flag_pwd == false) { // 舊密碼長度錯誤
                $('#' + formId + '-1-msg').addClass('visible');
                $('#' + formId + '-1-msg').removeClass('invisible');
                return false;
            } else {
                $('#' + formId + '-1-msg').addClass('invisible');
                $('#' + formId + '-1-msg').removeClass('visible');
            }
            if (flag_pwd_new == false) { // 新密碼長度錯誤
                $('#' + formId + '-2-msg').addClass('visible');
                $('#' + formId + '-2-msg').removeClass('invisible');
                $('#' + formId + '-2-msg').text($('#' + formId + '-2-msg').data('msg01'));
                return false;
            } else {
                $('#' + formId + '-2-msg').addClass('invisible');
                $('#' + formId + '-2-msg').removeClass('visible');
            }
            if (flag_pwd_chk == true) { // 新舊密碼一致
                $('#' + formId + '-2-msg').addClass('visible');
                $('#' + formId + '-2-msg').removeClass('invisible');
                $('#' + formId + '-2-msg').text($('#' + formId + '-2-msg').data('msg02'));
                return false;
            } else {
                $('#' + formId + '-2-msg').addClass('invisible');
                $('#' + formId + '-2-msg').removeClass('visible');
            }


            if (flag_pwd && flag_pwd_new) {
                console.log('可以送');
                form.submit();
            } else {
                console.log('不能送');
            };
        }
    }
</script>

@endsection