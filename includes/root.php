<?php
const ROOT_PATH = '/ship-app';

$ssl    = empty( $_SERVER[ 'HTTPS' ] ) ? '' : 's';
$uri    = $_SERVER[ 'REQUEST_URI' ];
$path   = strpos( $uri, ROOT_PATH ) === FALSE ? '' : ROOT_PATH;
$host   = $_SERVER[ 'HTTP_HOST' ];
$root   = "http$ssl://$host$path";
$assets = "$root/assets/";
