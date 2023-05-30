<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/funcs.php';

if (isset($_GET['page'])) {
    $page = (int)$_GET['page'];
    $per_page = 200;
    $start = get_start($page, $per_page);
    $cities = get_cities($start, $per_page);
    $html = '';
    if ($cities) {
        foreach ($cities as $city) {
            $html .= "<li>{$city['name']}: {$city['population']}</li>";
        }
    }
    echo $html;
    die;
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>AJAX Load</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css">
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <style>
        .loader {
            display: none;
        }
        .loader img {
            width: 100px;
        }
        .show {
            display: block;
        }
    </style>
</head>
<body>

<div class="container-fluid mb-3">
    <ul id="cities"></ul>

    <div class="loader">
        <img src="ripple.svg" alt="">
    </div>

    <button class="btn btn-primary" id="load">Load more</button>
</div>

<footer style="background-color: #000; height: 200px; padding: 0;"></footer>

<script>
    const citiesContainer = document.getElementById('cities');
    const btnLoad = document.getElementById('load');
    const loader = document.querySelector('.loader');
    let page = 1;
    let lock = false;

    async function getCities() {
        const res = await fetch(`index.php?page=${page}`);
        return res.text();
    }

    // getCities();
    async function showCities() {
        const cities = await getCities();
        if (cities) {
            citiesContainer.insertAdjacentHTML('beforeend', cities);
            lock = false;
        } else {
            btnLoad.classList.add('d-none');
            lock = true;
        }
    }

    loader.classList.add('show');
    setTimeout(() => {
        showCities();
        loader.classList.remove('show');
    }, 1000);

    btnLoad.addEventListener('click', () => {
        loader.classList.add('show');
        setTimeout(() => {
            page++;
            showCities();
            loader.classList.remove('show');
        }, 1000);
    });

    window.addEventListener('scroll2', () => {
        const {scrollTop, scrollHeight, clientHeight} = document.documentElement;
        // console.log(scrollTop, scrollHeight, clientHeight);
        if (scrollTop + clientHeight >= scrollHeight) {
            if (false === lock) {
                lock = true;
                loader.classList.add('show');
                setTimeout(() => {
                    page++;
                    showCities();
                    loader.classList.remove('show');
                }, 1000);
            }
        }
    });

</script>

</body>
</html>
