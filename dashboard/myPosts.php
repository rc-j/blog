<?php
session_start();
include '../include/connection.php';
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}
include '../include/header.php';
if (isset($_GET['post_id'])) {
    $query = 'SELECT picture FROM posts WHERE post_id = :postID';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postID', $_GET['post_id']);
    $stmt->execute();
    $row = $stmt->fetch();
    unlink(realpath(dirname(getcwd())) . "/assets/" . $row['picture']);
    $query = 'DELETE FROM posts WHERE post_id = :postID';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':postID', $_GET['post_id']);
    $stmt->execute();
    header("Location: myPosts.php");
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="position-fixed col-lg-3 col-xl-2 p-0">
            <?php
            include '../include/adminSidebar.php';
            ?>
        </div>
        <div style="position: absolute; z-index: -1;" class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10 p-0">
            <div class="row">
                <?php
                // Set Current Page Variable
                isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
                // Start & Limit Of Retrieved Date
                $limit = 6;
                $start = ($page - 1) * $limit;
                // Get Total Page
                $query = 'SELECT * FROM posts WHERE author = :author';
                $stmt = $db->prepare($query);
                $stmt->bindParam(':author', $_SESSION['admin']);
                $stmt->execute();
                $totalPages = ceil($stmt->rowCount() / $limit);
                // Fetch myPosts
                $query = 'SELECT * FROM posts WHERE author = :author ORDER BY post_id DESC LIMIT ' . $start . ', ' . $limit;
                $stmt = $db->prepare($query);
                $stmt->bindParam(':author', $_SESSION['admin']);
                $stmt->execute();

                while ($row = $stmt->fetch()) {
                ?>
                    <div class="col-5 m-3">
                        <div class="card">
                            <img src="<?= '/project/assets/' . $row['picture']; ?>" class="card-img-top" alt="">
                            <div class="card-body">
                                <h5 class="card-title text-center fs-3"><?= $row['title']; ?></h5>
                                <p class="card-text"> <?= empty($row['picture']) ? substr(nl2br($row['content']), 0, 1000) . ' ...' : substr(nl2br($row['content']), 0, 269) . ' ...'; ?>
                                </p>
                                <a href="<?= '/project/dashboard/myPosts.php?post_id=' . $row['post_id']; ?>" class="btn btn-danger">Delete</a>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
            <!-- Pagination -->
            <div class="row">
                <div class="col">
                    <ul class="pagination d-flex justify-content-center mt-5">
                        <li class="page-item <?= $page - 1 == 0 ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
                        </li>
                        <?php
                        for ($i = 1; $i <= $totalPages; $i++) {
                        ?>
                            <li class="page-item <?= $page == $i ? 'active' : ''; ?>">
                                <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                            </li>
                        <?php
                        } ?>
                        <li class="page-item <?= $page + 1 > $totalPages ? 'disabled' : ''; ?>">
                            <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
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