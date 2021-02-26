<?php

$address='localhost';
$login='root';
$password='';
$database='statki';

$connect=new mysqli($address, $login, $password, $database);
mysqli_report(MYSQLI_REPORT_STRICT);