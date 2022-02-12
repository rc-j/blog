<?php
session_start();
include 'include/connection.php';
include 'include/header.php';
if(!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}
?>
<!-- Check request -->
<!-- Contents -->
<?php
include 'include/footer.php'
?>