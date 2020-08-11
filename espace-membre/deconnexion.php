<?php
session_start();

require_once('../core/account.php');

if ($_SESSION['wantMpChange']) {

    deleteSession();

    header('Location: /mp.php');
    exit();
} else {

    deleteSession();

    header('Location: /index.php');
    exit();
}
