@extends("front.app")
@section("title", "Explore")
@section("content")
<!-- 地圖 JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="/js/leaflet.markercluster.js"></script>
<!-- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<style>

</style>

<div class="container">
    <div class="row">
        <div class="col-md-7 z-1">
            <div class="" id="map" style="height: 100vh;"></div>
        </div>
        <div class="col-md-5 overflow-y-scroll" id="sec-data" style="height: 100vh;">
            <div class="d-flex justify-content-between">
                <div class="fw-bold text-neutral-900 text-3xl mb-3 text-capitalize">{{ $cityName }}</div>
                <div><span id="showItemsCount">{{ count($list) }}</span>/{{ count($list) }} 筆</div>
            </div>
            <div class="col-12 mb-3">
                <div class="fw-bold text-muted text-uppercase"><small>Filter by</small></div>
                <select class="form-select shadow-lg" id="districtList" name="districtList">
                    <option value="" class="disabled">-- 請選擇縣市 --</option>
                </select>
            </div>
            <div class="col-12">
                <div class="fw-bold text-neutral-900 text-xl mb-3">Room type <span class="text-muted text-sm">房間類型</span></div>
                <div class="row">
                    <div class="col-md-12">
                        <canvas id="myChart01"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="fw-bold text-neutral-900 text-xl mb-3">Price <span class="text-muted text-sm">價格區間</span></div>
                <div class="row">
                    <div class="col-md-12">
                        <canvas id="myChart02"></canvas>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<script>
    const navbar = $('#navbar');
    const map = $('#map');
    const secData = $('#sec-data');
    map.attr('style', `height: calc(100vh - ${navbar.outerHeight(true)}px);`); // 設定內容 section 高度
    secData.attr('style', `height: calc(100vh - ${navbar.outerHeight(true)}px);`); // 設定內容 section 高度

    $(document).ready(function() {
        // airbnb 資料
        var propertyData = [];
        // 依區域名稱分類
        var districtTitle = [];
        var counter = [];
        var districtData = [];
        // 依建築類型分類
        var propertyTypeTitle = [];
        var counter2 = [];
        var propertyTypeData = [];
        // 依房型分類
        var roomTypeTitle = [];
        var counter3 = [];
        var roomTypeData = [];
        // 依價格分類
        var interval = 10000; // 級距
        var itvStart = 1; // 級距起始值
        var itvEnd = 99; // 級距結束值
        var priceTitle = [];
        var counter4 = [];
        var priceData = [];

        // 顯示筆數
        var totalItems;

        // 地圖
        var map;
        var markers;

        // 圖表
        const ctx01 = document.getElementById('myChart01'); //$("#myChart01")
        var mychart01 = new Chart(ctx01, {
            type: 'bar',
            data: {
                labels: ['label01', 'label02', 'label03', 'label04', 'label05', 'label06'],
                datasets: [{
                    label: '數量 (間)',
                    data: [120, 19, 3, 5, 2, 30],
                    borderWidth: 1,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                    datalabels: {
                        display: 'auto', //要不要顯示數值以及方式，*註1
                        color: '#005599', //深藍色
                        backgroundColor: 'rgb(210,210,210,0.8)', //淺灰色，微透明
                        labels: {
                            title: {
                                font: {
                                    weight: 'bold' //粗體
                                }
                            }
                        },
                        anchor: 'end', //錨點，在畫完圖的後方，*註2
                        align: 'end', //對齊，在最末端，*註2
                        offset: 4 //位置調整，*註2
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10 // 每 10 為一個級距
                        }
                    }
                },
                plugins: [ChartDataLabels] // 註冊插件
            }
        });
        const ctx02 = document.getElementById('myChart02'); //$("#myChart02")

        var mychart02 = new Chart(ctx02, {
            type: 'doughnut',
            data: {
                labels: [
                    'Red',
                    'Blue',
                    'Yellow',
                ],
                datasets: [{
                    label: '區間',
                    data: [300, 50, 100],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.6)', // Red
                        'rgba(54, 162, 235, 0.6)', // Blue
                        'rgba(255, 206, 86, 0.6)', // Yellow
                    ],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true, // 自適應大小
                cutout: '70%', // 調整內環的大小，值越大甜甜圈越細
                plugins: {
                    legend: {
                        position: 'top', // 圖例顯示位置
                    },
                    tooltip: {
                        enabled: true // 啟用提示框
                    }
                }
            },
        });

        // 分類資料
        propertyData = @json($list);
        // console.log(propertyData);

        propertyData.forEach(item => {
            // 依區域分類資料
            if (counter[item.neighbourhood_cleansed] == undefined) {
                counter[item.neighbourhood_cleansed] = districtData.length;
                districtData.push(new Array());
                districtTitle[counter[item.neighbourhood_cleansed]] = item.neighbourhood_cleansed;
            }
            districtData[counter[item.neighbourhood_cleansed]].push(item);

            // 依房型分類資料
            if (counter3[item.room_type] == undefined) {
                counter3[item.room_type] = roomTypeData.length;
                roomTypeData.push(new Array());
                roomTypeTitle[counter3[item.room_type]] = item.room_type;
            }
            roomTypeData[counter3[item.room_type]].push(item);

            // 依價格分類資料
            itvStart = Math.floor(parseFloat(item.price) / interval) * interval;
            itvEnd = Math.floor(parseFloat(item.price) / interval) * interval + (interval - 1);
            // console.log("itvStart: " + itvStart + " " + "itvEnd: " + itvEnd);
            if (counter4[`${itvStart}-${itvEnd}`] == undefined) {
                counter4[`${itvStart}-${itvEnd}`] = priceData.length;
                priceData.push(new Array());
                priceTitle[counter4[`${itvStart}-${itvEnd}`]] = `${itvStart}-${itvEnd}`;
            }
            priceData[counter4[`${itvStart}-${itvEnd}`]].push(item);
        });

        renderDistrictList();
        renderMap(propertyData);

        // 渲染圖表
        showdata_chart01(propertyData, mychart01);
        showdata_chart02(propertyData, mychart02);

        $("#districtList").change(function() {
            currDistrict = $(this).val();
            showDataList = districtData[districtTitle.indexOf(currDistrict)];
            renderMap(showDataList);

            // 渲染顯示筆數
            renderTotalItems(showDataList);

            showdata_chart01(showDataList, mychart01);
            showdata_chart02(showDataList, mychart02);
            // showPriceData(showDataList);
            // showRoomCount(showDataList, "#count-rooms"); // 顯示分類間數
            // showRate(); // 顯示間數占比
        });


        // 產生地圖
        function renderMap(dataList) {
            // console.log(dataList);
            if (map != undefined) map.remove();

            // 最初水滴座標
            map = L.map('map').setView([dataList[0].latitude, dataList[0].longitude], 13);
            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            map.panTo([dataList[0].latitude, dataList[0].longitude]);

            $.each(dataList, function(i, item) {
                L.marker([item.latitude, item.longitude]).addTo(map)
                    .bindPopup(
                        '<div class="card" style="width: 15rem;">' +
                        '<img src="' + item.picture_url + '" class="card-img-top bg-cover" alt="" style="max-height: 8rem;" onError="this.onerror=null; this.src=\'/images/mid-autumn-godgwawa.jpg\'" >' +
                        '<div class="card-body">' +
                        '<div class="d-flex justify-content-between align-items-center">' +
                        '<span class="badge text-bg-primary">' + item.neighbourhood_cleansed + '</span>' +
                        (item.liked === 'Y' ?
                            '<i class="fa-solid fa-bookmark" onclick="toggleLike(\'N\')" style="cursor: pointer"></i>' :
                            '<i class="fa-regular fa-bookmark" onclick="toggleLike(\'Y\')" style="cursor: pointer"></i>') +
                        '</div>' +
                        '<div class="text-truncate">' +
                        '<a href="' + item.listing_url + '" target="_blank">' + item.name + '</a>' +
                        '</div>' +
                        '<div class="row text-truncate">' +
                        '<div class="col-md-3 text-sm text-muted">建築類型</div>' +
                        '<div class="col-md-9 text-sm text-muted text-end">' + item.property_type + '</div>' +
                        '</div>' +
                        '<div class="row text-truncate">' +
                        '<div class="col-md-3 text-sm text-muted">房間類型</div>' +
                        '<div class="col-md-9 text-sm text-muted text-end">' + item.room_type + '</div>' +
                        '</div>' +
                        '<div class="row text-truncate">' +
                        '<div class="col text-sm fw-bold">$' + ((item.price == null) ? '--' : Math.floor(parseFloat(item.price)).toLocaleString()) + ' 晚</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>'
                    );
            });

            markers = new L.markerClusterGroup().addTo(map);
        }

        // 清除所有的水滴座標
        function removeMaker() {
            map.eachLayer(function(layer) {
                if (layer instanceof L.Marker) {
                    map.removeLayer(layer)
                }
            });
        }

        // 產生區域選單
        function renderDistrictList() {
            $("#districtList").empty();
            $("#districtList").append('<option value="" disabled selected>-- 請選擇縣市 --</option>');
            districtTitle.forEach(function(item) {
                var strHTML = '<option value="' + item + '">' + item + '</option>';
                $("#districtList").append(strHTML);
            });
        }

        // 渲染圖表
        function showdata_chart01(data, mychart) {
            // console.log(data);

            // 清空圖表
            mychart.data.labels = [];
            mychart.data.datasets[0].data = [];

            // 重新計算房型數量
            let tempCounter = [];
            data.forEach(item => {
                tempCounter[item.room_type] = (tempCounter[item.room_type] || 0) + 1;
            });

            // 更新資料
            mychart.data.labels = roomTypeTitle;
            mychart.data.datasets[0].data = Object.values(tempCounter);
            mychart.update();
        }

        function showdata_chart02(data, mychart) {
            let tempCounter = [];
            // 清空圖表
            mychart.data.labels = [];
            mychart.data.datasets[0].data = [];

            // 重新計算級距數量
            // 清空資料
            priceTitle = [];
            counter4 = [];
            priceData = [];
            data.forEach(item => {
                // 依價格分類資料
                itvStart = Math.floor(parseFloat(item.price) / interval) * interval;
                itvEnd = Math.floor(parseFloat(item.price) / interval) * interval + (interval - 1);
                // console.log("itvStart: " + itvStart + " " + "itvEnd: " + itvEnd);
                if (counter4[`${itvStart}-${itvEnd}`] == undefined) {
                    counter4[`${itvStart}-${itvEnd}`] = priceData.length;
                    priceData.push(new Array());
                    priceTitle[counter4[`${itvStart}-${itvEnd}`]] = `${itvStart}-${itvEnd}`;
                }
                priceData[counter4[`${itvStart}-${itvEnd}`]].push(item);
            });

            // 如果級距數量大於 mychart02 原本資料顏色數量，就隨機生成
            if (priceTitle.length > (mychart02.data.datasets[0]).backgroundColor.length) {
                let lengthToAdd = priceTitle.length - (mychart02.data.datasets[0]).backgroundColor.length;
                for (var i = 0; i <= lengthToAdd; i++) {
                    (mychart02.data.datasets[0]).backgroundColor.push(getRandomColor());
                }
            }

            priceData.forEach(arr => {
                tempCounter.push(arr.length);
            });

            // 更新資料
            mychart.data.labels = priceTitle;
            mychart.data.datasets[0].data = Object.values(tempCounter);
            mychart.update();
        }

        // 渲染顯示數量
        function renderTotalItems(array) {
            totalItems = array.length;
            $("#showItemsCount").text(totalItems);
        }


        // 隨機產生 RGBA 顏色
        function getRandomColor() {
            const r = Math.floor(Math.random() * 256);
            const g = Math.floor(Math.random() * 256);
            const b = Math.floor(Math.random() * 256);
            return `rgba(${r}, ${g}, ${b}, 0.6)`;
        }

        // 產生顏色組合（背景色+邊框色）
        function generateRandomColors(count) {
            const backgroundColors = [];
            const borderColors = [];

            for (let i = 0; i < count; i++) {
                const baseColor = getRandomColor();
                backgroundColors.push(`${baseColor}, 0.6)`);
                borderColors.push(`${baseColor}, 1)`);
            }

            return {
                backgroundColors,
                borderColors
            };
        }
    });
</script>
@endsection