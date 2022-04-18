<?php
session_start();
include '../include/connection.php';
if (!isset($_GET['category'])) {
    header('Location: index.php');
    exit();
}
include '../include/header.php';
$category = $_GET['category'];
// Set Current Page Variable
isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
// Start & Limit Of Retrieved Date
$limit = 4;
$start = ($page - 1) * $limit;
// Get Total Pages
$query = 'SELECT * FROM posts WHERE category = :category';
$stmt = $db->prepare($query);
$stmt->bindParam(':category', $category);
$stmt->execute();
$totalPages = ceil($stmt->rowCount() / $limit);
?>
<div class="container-fluid">
    <div class="row">
        <div class="position-fixed col-lg-3 col-xl-2 p-0">
            <?php
            if (isset($_SESSION['admin'])) {
                include '../include/adminSidebar.php';
            } else {
                include '../include/sidebar.php';
            }
            ?>
        </div>
        <div style="position: absolute; z-index: -1;" class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10 p-0">
            <div class="row">
                <?php
                // Fetch categories
                $query = 'SELECT * FROM posts WHERE category = :category ORDER BY post_id DESC LIMIT ' . $start . ', ' . $limit;
                $stmt = $db->prepare($query);
                $stmt->bindParam(':category', $category);
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                ?>
                    <div class="col-5 m-3">
                        <div class="card">
                            <img src=<?= '../assets/' . $row['picture']; ?> class="card-img-top" alt="">
                            <div class="card-body">
                                <h5 class="card-title fs-3 text-center"><?= $row['title']; ?></h5>
                                <p class="card-text"> <?= empty($row['picture']) ? substr(nl2br($row['content']), 0, 1000) . ' ...' : substr(nl2br($row['content']), 0, 269) . ' ...'; ?>
                                </p>
                                <a href="<?= '/project/dashboard/post.php?post_id=' . $row['post_id']; ?>" class="btn btn-info">See more...</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <div class="row">
                <div class="col">
                    <ul class="pagination d-flex justify-content-center mt-5">
                        <li class="page-item <?= $page - 1 == 0 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?category=<? $category ?>&page=<?= $page - 1; ?>">Previous</a>
                        </li>
                        <?php
                        for ($i = 1; $i <= $totalPages; $i++) {
                        ?>
                            <li class="page-item <?= $page == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?category=<?= $category ?>&page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php
                        } ?>
                        <li class="page-item <?= $page + 1 > $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?category=<?= $category ?>&page=<?= $page + 1; ?>">Next</a>
                        </li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</div>
<?php
include '../include/footer.php';
?>