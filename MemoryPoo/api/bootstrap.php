<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/autoload.php';

ini_set('display_errors', 0);
error_reporting(0);

session_start();

header('Content-Type: application/json; charset=utf-8');

return true;
