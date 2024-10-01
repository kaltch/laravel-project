<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield("title")</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <link rel="stylesheet" href="/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/app.css">
    <!-- 地圖 -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="/css/MarkerCluster.css">
    <!-- Air datepicker -->
    <link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.css" rel="stylesheet">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400&display=swap" rel="stylesheet">
    <!-- toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">

    <style>
        /* 處理破圖 */
        img[src=''],
        img:not([src]) {
            /* Set a default image source */
            background-image: url('/images/mid-autumn-godgwawa.jpg');
        }

        .ubuntu-regular {
            font-family: "Ubuntu", sans-serif;
            font-weight: 400;
            font-style: normal;
        }
    </style>
</head>

<body>
    <!-- Bootstrap JS -->
    <script src="/js/bootstrap.bundle.min.js"></script>
    <!-- jQuery -->
    <script src="/js/jquery-3.7.1.min.js"></script>
    <!-- Air datepicker -->
    <script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.js"></script>
    <!-- toastify -->
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    @if(Session::has("message"))
    <script>
        Toastify({
            text: "{{ Session::get('message') }}",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: false,
            style: {
                background: "linear-gradient(to right, #00b09b, #96c93d)",
            },
        }).showToast();
    </script>
    @endif

    @if(Session::has("errorMessage"))
    <script>
        Toastify({
            text: "{{ Session::get('errorMessage') }}",
            duration: 3000,
            close: true,
            gravity: "top",
            position: "right",
            stopOnFocus: false,
            style: {
                // background: "linear-gradient(to right, #00b09b, #96c93d)",
                background: "#ea5a5a",
            },
        }).showToast();
    </script>
    @endif

    <!-- Navigation Bar -->
    @include("Front.menu")
    <!-- Content -->
    @yield("content")


</body>

</html>