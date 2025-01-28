<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'vinyl_shop');
define('DB_USER', 'root');
define('DB_PASS', '');

define('SITE_NAME', 'Vinyl Shop');
define('SITE_URL', 'http://localhost/vinyl_shop');

ini_set('session.cookie_lifetime', 0);
ini_set('session.use_cookies', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 1);
ini_set('session.use_trans_sid', 0);
ini_set('session.cache_limiter', 'nocache');

error_reporting(E_ALL);
ini_set('display_errors', 1);

date_default_timezone_set('Europe/Skopje');

define('DISCOGS_API_KEY', 'bqovWclzoOJTphftqCjA'); 
define('DISCOGS_API_SECRET', 'XHoHDruHdiGZcDWdxTrWkvCFXMiKufOY'); 
define('DISCOGS_USER_AGENT', 'VinylShop/1.0'); 

function getDiscogsInfo($artist, $title) {
    $url = "https://api.discogs.com/database/search?" . 
           "artist=" . urlencode($artist) . 
           "&release_title=" . urlencode($title) . 
           "&key=" . DISCOGS_API_KEY . 
           "&secret=" . DISCOGS_API_SECRET;
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, DISCOGS_USER_AGENT);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return json_decode($response, true);
}

ini_set('default_charset', 'UTF-8');

ini_set('upload_max_filesize', '5M');
ini_set('post_max_size', '6M');

header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('X-Content-Type-Options: nosniff');

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
