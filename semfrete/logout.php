<?php
session_start();
if (isset($_GET['sair'])) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
} else {
    header("Location: index.php");
    exit();
}