<?php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'mrspeedy';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_errno) {
   die('Conectare la MySQL eșuată: ' . $conn->connect_error);
}
$conn->set_charset('utf8mb4');
