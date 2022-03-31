<?php
$query = 'SELECT category_name FROM categories ORDER BY category_id DESC';
?>
<div class="position-fixed col-lg-3 col-xl-2 p-0">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="sidebar">
            <ul class="navbar-nav flex-column align-self-start" style="height: 100vh; overflow: auto;">
                <li class="nav-item ms-3">
                    <a href="<?= '/project/dashboard/'; ?>" class="nav-link me-5" href="#">
                        Login <i class="fa-solid fa-right-to-bracket"></i></a>
                </li>
                <li class="nav-item ms-3 mt-5 text-secondary">
                    Select catogery
                </li>
                <?php
                foreach ($db->query($query) as $row) {
                ?>
                    <li class="nav-item ms-3">
                        <a class="nav-link" href="<?= '/project/dashboard/categories.php?category=' . $row['category_name']; ?>">
                            <?= $row['category_name']; ?>
                        </a>
                    </li>
                <?php
                }
                ?>
            </ul>
        </div>
    </nav>
</div>