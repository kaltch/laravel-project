
<?php $__env->startSection('title', 'Search Results'); ?>
<?php $__env->startSection('content'); ?>
<!-- 地圖 JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
    integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script src="/js/leaflet.markercluster.js"></script>
<!-- chart js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>

<style>
    .list-item-img {
        height: 160px;
    }
</style>

<div class="container">
    <div class="row">
        <div class="col-md-6 vh-100 overflow-y-scroll">
            <!-- <div class="fw-bold text-neutral-900 text-3xl mb-3 text-capitalize"><?php echo e($cityName); ?></div> -->

            <?php $__currentLoopData = $list; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $data): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-12 border-bottom">
                <a href="<?php echo e($data->listing_url); ?>" target="_blank" style="text-decoration: none; color: inherit;">
                    <div class="row my-3">
                        <div class="col-md-4">
                            <div class="img-fluid bg-cover bg-position-center list-item-img rounded" style="background-image: url('<?php echo e($data->picture_url); ?>');"></div>
                        </div>
                        <div class="col-md-8 d-flex flex-column justify-content-around text-break">
                            <div class="row">
                                <div class="col">
                                    <div class="text-sm text-muted">位於 <?php echo e($data->neighbourhood_cleansed); ?> 的 <?php echo e($data->property_type); ?></div>
                                </div>
                            </div>
                            <div class="fw-bold text-neutral-900 text-base"><?php echo e($data->name); ?></div>
                            <div class="text-sm text-muted"><?php echo e($data->bedrooms); ?> bedrooms • <?php echo e($data->bathrooms_text); ?> • <?php echo e($data->accommodates); ?> guests</div>
                            <div class="fw-bolder text-xl">$<?php echo e(($data->price > 100) ? (number_format($data->price, 0)) : '--'); ?> <span class="text-sm text-muted">/ 平均每晚</span></div>
                            <div>
                                <button class="btn btn-primary">查看詳情</button>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </div>
        <div class="col-md-6 z-1">
            <div class="vh-100" id="map"></div>
        </div>
    </div>


</div>

</div>
</div>
<script>
    $(document).ready(function() {
        var currDistrict; // 選取區域名稱

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
        var interval = 1000; // 級距
        var itvStart = 1; // 級距起始值
        var itvEnd = 99; // 級距結束值
        var priceTitle = [];
        var counter4 = [];
        var priceData = [];

        // 地圖
        var map;
        var markers;

        // 分類資料
        propertyData = <?php echo json_encode($list, 15, 512) ?>;
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

        renderMap(propertyData);


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


    });
</script>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('Front.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/resources/views/Front/data/search.blade.php ENDPATH**/ ?>