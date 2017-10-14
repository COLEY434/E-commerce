<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/E-commerce/core/connect.php';
unset($_SESSION['user']);
header('Location: login.php');




?>
