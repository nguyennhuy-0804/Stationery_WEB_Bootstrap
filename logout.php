<?php
session_start();

if (isset($_SESSION['mySession'])) {
    unset($_SESSION['mySession']);
    unset($_SESSION['user']);
}

header('location:login.php');
