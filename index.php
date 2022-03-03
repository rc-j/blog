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
        <div class="position-fixed col-lg-3 col-xl-2 p-0">
            <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="sidebar">
                    <ul class="navbar-nav flex-column align-self-start" style="height: 100vh; overflow: auto;">
                        <li class="nav-item ms-3">
                            <a href="dashboard/" class="nav-link me-5" href="#">
                                Login <i class="fa-solid fa-right-to-bracket"></i></a>
                        </li>
                        <li class="nav-item ms-3 mt-5 text-secondary">
                            Select catogery
                        </li>
                        <li class="nav-item ms-3">
                            <a class="nav-link" href="#">
                                AAAAAAAAAAAAAAA17... </a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
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