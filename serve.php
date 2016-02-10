<?php
// Set timezone
date_default_timezone_set("UTC");

// Directory that contains error pages
define("ERRORS", dirname(__FILE__) . "/errors");

// Default index file
define("DIRECTORY_INDEX", "index.php");

// Optional array of authorized client IPs for a bit of security
$config["hostsAllowed"] = array();

function logAccess($status = 200) {
    file_put_contents("php://stdout", sprintf("[%s] %s:%s [%s]: %s\n",
        date("D M j H:i:s Y"), $_SERVER["REMOTE_ADDR"],
        $_SERVER["REMOTE_PORT"], $status, $_SERVER["REQUEST_URI"]));
}

// Parse allowed host list
if (!empty($config['hostsAllowed'])) {
    if (!in_array($_SERVER['REMOTE_ADDR'], $config['hostsAllowed'])) {
        logAccess(403);
        http_response_code(403);
        echo "403";
        exit;
    }
}

// if requesting a directory then serve the default index
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);
if (empty($ext)) {
    $path = rtrim($path, "/") . "/" . DIRECTORY_INDEX;
}

// If the file exists then return false and let the server handle it
if (file_exists($_SERVER["DOCUMENT_ROOT"] . 'public' . DIRECTORY_SEPARATOR . $path)) {
    return false;
}

logAccess();
require_once __DIR__.'/public/index.php';
