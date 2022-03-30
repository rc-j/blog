<div class="row">
    <?php
    // Set Current Page Variable
    isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
    // Start & Limit Of Retrieved Date
    $limit = 6;
    $start = ($page - 1) * $limit;
    // Get Total Pages
    $stmt = $db->query('SELECT * FROM posts');
    $totalPages = ceil($stmt->rowCount() / $limit);
    // Fetch Categories
    $stmt = $db->query('SELECT * FROM posts ORDER BY post_id DESC LIMIT ' . $start . ', ' . $limit);
    while ($row = $stmt->fetch()) {
    ?>
        <div class="col-5 m-3">
            <div class="card">
                <img src="<?= '/project/assets/' . $row['picture']; ?>" class="card-img-top" alt="">
                <div class="card-body">
                    <h5 class="card-title text-center fs-3"><?= $row['title']; ?></h5>
                    <p class="card-text"> <?= empty($row['picture']) ? substr(nl2br($row['content']), 0, 1000) . ' ...' : substr(nl2br($row['content']), 0, 269) . ' ...'; ?>
                    </p>
                    <a href="<?= '/project/dashboard/post.php?post_id=' . $row['post_id']; ?>" class="btn btn-info">See more...</a>
                    <i class="fa-solid fa-user ms-2"></i>
                    <?= $row['author'] . ' <i class="fa-regular fa-calendar-days ms-2"></i>  ' . $row['date'] ?>
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