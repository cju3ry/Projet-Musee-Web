<?php
session_start();
include("../fonction/fonction.php");
if ($_SESSION['loginAdmin'] != "") {
    echo $_SESSION['loginAdmin'];
}
if($_SESSION['loginEmploye'] != ""){
    echo $_SESSION['loginEmploye'];
}
session_destroy();
?>