<?php
session_start();
if (isset($_SESSION['admin'])) {
    header('Location: dashboard/');
    exit();
}
include 'include/connection.php';
include 'include/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <?php
        include 'include/sidebar.php';
        ?>
        <div style="position: absolute; z-index: -1;" class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10 p-0">
            <?php
            include 'include/posts.php';
            ?>
        </div>
    </div>
</div>

<?php
include 'include/footer.php';
?>