<?php
session_start();

include("account.php");

if ($_SESSION['wantMpChange']) {

    deleteSession();

    header('Location: ../mp.php');
} else {

    deleteSession();

    header('Location: ../index.php');
}
