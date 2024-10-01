@extends('front.app')
@section('title', 'Home')
@section('content')
<style>
    .search-bar {
        background-color: #f4f4f4;
        padding: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 800px;
    }

    .search-item {
        flex: 1;
        padding: 0 10px;
    }

    .search-item label {
        display: block;
        color: #333;
    }

    .search-item input {
        border: none;
        background-color: transparent;
        outline: none;
        width: 100%;
    }

    .search-btn {
        background-color: #ff5a5f;
        border: none;
        cursor: pointer;
        /* margin-left: 10px; */
        width: 40px;
        height: 40px;
        transition: background-color 0.3s;
    }

    .search-btn:hover {
        background-color: #e0484e;
    }

    .search-icon {
        font-size: 20px;
        font-weight: bold;
    }

    .search-bar .search-item:not(:last-child) {
        border-right: 1px solid #e0e0e0;
    }

    .search-item input[type='number'] {
        -moz-appearance: textfield;
    }

    .search-item input[type='number']::-webkit-outer-spin-button,
    .search-item input[type='number']::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
</style>

<div class='container'>
    <div class='row'>
        <div class='col-md-6' style='height: 90vh;'>
            <h1 class="fw-bold">隨時隨地，<br />發現你的完美住宿</h1>
            <p class="text-muted">不論是旅行愛好者，<br />
                或是嚮往悠閒舒適的本地度假旅程，<br />
                都能像在家一樣自在，探索世界也能享受當地生活</p>
            <div class='toggle-switch'>
                <div id='switch' class='switch-btn'></div>
                <span id='searchText' class='fw-bold toggle-text'>搜尋</span>
                <span id='urlText' class='fw-bold toggle-text'>輸入網址</span>
            </div>

            <div class='mt-3'>
                <!-- 搜尋 -->
                <form action='/properties/search' method='get' id='form01'>
                    <div class='search-bar d-flex align-items-center justify-content-between rounded-pill'>
                        <!-- {{ csrf_field() }} -->
                        <div class='search-item'>
                            <label class='text-xs'>地點</label>
                            <input type="hidden" id='location' name='location'>
                            <input type='text' placeholder='搜尋目的地' class='text-sm' id='location-text' name='location-text'
                                autocomplete='off' list='list01'>
                            <datalist id='list01'>
                                <option data-value='1'>台北 Taipei</option>
                            </datalist>
                        </div>
                        <div class='search-item'>
                            <label class='text-xs'>入住</label>
                            <!-- Air datepicker Html element -->
                            <input id='myDatepicker01' data-language='en' placeholder='選擇日期' class='text-sm' name='checkin'>
                        </div>
                        <div class='search-item'>
                            <label class='text-xs'>退房</label>
                            <input id='myDatepicker02' data-language='en' placeholder='選擇日期' class='text-sm' name='checkout'>
                        </div>
                        <div class='search-item'>
                            <label class='text-xs'>入住人數</label>
                            <input type='number' value='2' min='1' class='text-sm' name='adults'>
                        </div>
                        <button class='search-btn rounded-circle text-white d-flex justify-content-center align-items-center ms-2'
                            onclick='sendData("form01")'>
                            <span class='search-icon'><i class="fa-solid fa-magnifying-glass"></i></span>
                        </button>
                    </div>
                </form>

                <!-- 輸入網址 -->
                <form action='/properties/search/url' method='get' id='form02'>
                    <div class='search-bar d-flex align-items-center justify-content-between rounded-pill'>
                        <!-- {{ csrf_field() }} -->
                        <div class='search-item'>
                            <input type='text' class='text-sm' id='input-url' name='url' placeholder='請輸入網址' style='text-decoration: none;'>
                        </div>
                        <button class='search-btn rounded-circle text-white d-flex justify-content-center align-items-center ms-2'
                            onclick='sendData("form02")'>
                            <span class='search-icon'><i class="fa-solid fa-magnifying-glass"></i></span>
                        </button>
                    </div>
                </form>

                <div class='text-sm text-danger mt-3 ms-2' id='text-msg'>
                    請輸入正確資訊
                </div>
            </div>
        </div>
        <div class='col-md-6' style='height: 90vh;'>
            <div class='img-fluid bg-cover bg-position-center rounded-bottom-left-xxl' style='background-image: url("/images/home-01.jpg");'>
            </div>
        </div>
    </div>

    <script>
        var flagLocation = true; // 開放空白
        var flagLocationText = false;
        var flagCheckin = true; // 暫時開放空白
        var flagCheckout = true; // 暫時開放空白
        var flagAdults = false;
        var flagUrl = false;

        $(document).ready(function() {
            // 預設隱藏
            $('#form02').hide();
            $('#text-msg').hide();

            // 監聽 location (驗證 datalist 輸入資料)
            $('#location-text').bind('input propertychange', function() {
                const val = verifiDatalist('location-text');
                if (val > 0) {
                    $('#location').val(val);
                }
            });
        });

        function sendData(formId) {
            event.preventDefault();
            let form = $('#' + formId).serializeArray();
            // console.log(form);

            if (formId == 'form01') {
                form.forEach(el => {
                    if (el.name === 'location-text' && el.value != '') flagLocationText = true;
                    if (el.name === 'checkin' && el.value != '') flagCheckin = true;
                    if (el.name === 'checkout' && el.value != '') flagCheckout = true;
                    if (el.name === 'adults' && el.value != '') flagAdults = true;
                });

                if (flagLocationText && flagCheckin && flagAdults && flagAdults && flagLocation) {
                    $('#text-msg').hide();
                    $('#' + formId).submit();
                } else {
                    $('#text-msg').show();
                }
            } else if (formId == 'form02') {
                const urlRegex = /^(?:http?:\/\/)|(?:https?:\/\/)/;
                form.forEach(el => {
                    // 檢查 form.name 是否符合正規表達式: https://開頭
                    if (el.name === 'url' && el.value != '' && urlRegex.test(el.value)) flagUrl = true;                
                    console.log(el.name);
                    console.log(el.value);
                });
                if (flagUrl) {
                    $('#text-msg').hide();
                    $('#' + formId).submit();
                } else {
                    $('#text-msg').show();
                }
            }


        }
    </script>

    <script>
        // 切換表單資料 (搜尋/輸入網址)
        const toggleSwitch = $('.toggle-switch');
        const switchBtn = $('#switch');
        const searchText = $('#searchText');
        const urlText = $('#urlText');

        toggleSwitch.on('click', function() {
            toggleSwitch.toggleClass('active');

            // 根據 toggleSwitch 有無 class active，切換對應的 form
            if (!toggleSwitch.hasClass('active')) {
                // 顯示 form01
                $('#form01').show();
                $('#form02').hide();
            } else {
                // 顯示 form01
                $('#form01').hide();
                $('#form02').show();
            }

        });
    </script>
    <script>
        // Call Air datepicker function
        const en = {
            days: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
            daysShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            daysMin: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'],
            months: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
            monthsShort: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
            today: 'Today',
            clear: 'Clear',
            dateFormat: 'MM/dd/yyyy',
            timeFormat: 'hh:mm aa',
            firstDay: 0
        }

        const datepicker01 = new AirDatepicker('#myDatepicker01', {
            locale: en, // Set language
        });
        const datepicker02 = new AirDatepicker('#myDatepicker02', {
            locale: en, // Set language
        });

        // const printCurrentTime = () => {
        //     console.log(datepicker01.selectedDates) // print selected date
        //     console.log(datepicker02.selectedDates) // print selected date
        // }
    </script>
    <script>
        // 驗證 datalist: 傳入 inputId，如果 option 的選項文字對照到 data-value，回傳 true，否則回傳 false
        function verifiDatalist(inputId) {
            const $input = $('#' + inputId),
                $options = $('#' + $input.attr('list') + ' option'),
                inputVal = $input.val();
            let verification = false;
            for (let i = 0; i < $options.length; i++) {
                const $option = $options.eq(i),
                    dataVal = $option.data('value'),
                    showWord = $option.text(),
                    val = $option.val();
                if (showWord == inputVal) {
                    verification = dataVal;
                }
            }
            return verification;
        }
    </script>
</div>
@endsection