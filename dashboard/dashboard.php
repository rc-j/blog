<?php
session_start();
include 'include/connection.php';
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}
include 'include/header.php';
?>
<!-- Check request -->
<!-- Contents -->
<?php
include 'include/footer.php';
?>