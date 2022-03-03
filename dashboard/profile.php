<?php
session_start();
include '../include/connection.php';
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}

include '../include/header.php';

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-xl-2 p-0">
            <?php
            include '../include/sidebar.php';
            ?>
        </div>

        <div class="col-auto">
            Welcome <?= $_SESSION['admin']; ?>!<br>
            You will edit your profile from here.
        </div>

    </div>
</div>

<?php
include '../include/footer.php';
?>