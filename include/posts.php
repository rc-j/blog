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
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <img src="/assets/<?= $row['picture']; ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title"><?= $row['title']; ?></h5>
                        <p class="card-text"><?= $row['content']; ?></p>
                        <a href="#" class="btn btn-secondary">See more...</a>
                    </div>
                    <?php if (isset($_SESSION['admin'])) {
                    ?>
                        <textarea placeholder="Add a comment... max. 500 characters" class="form-control" name="Comment" rows="2" cols="45"></textarea>
                        <button class="d-grid col-6 btn btn-primary mx-auto mt-2">Add comment</button>
                    <?php
                    }
                    ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <img src="/assets/047558.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                    <?php if (isset($_SESSION['admin'])) {
                    ?>
                        <textarea placeholder="Add a comment... max. 500 characters" class="form-control" name="Comment" rows="2" cols="45"></textarea>
                        <button class="d-grid col-6 btn btn-primary mx-auto mt-2">Add comment</button>
                    <?php
                    }
                    ?>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <img src="/assets/047558.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                    <?php if (isset($_SESSION['admin'])) {
                    ?>
                        <textarea placeholder="Add a comment... max. 500 characters" class="form-control" name="Comment" rows="2" cols="45"></textarea>
                        <button class="d-grid col-6 btn btn-primary mx-auto mt-2">Add comment</button>
                    <?php
                    }
                    ?>

                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <img src="/assets/047558.jpg" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                    <?php if (isset($_SESSION['admin'])) {
                    ?>
                        <textarea placeholder="Add a comment... max. 500 characters" class="form-control" name="Comment" rows="2" cols="45"></textarea>
                        <button class="d-grid col-6 btn btn-primary mx-auto mt-2">Add comment</button>
                    <?php
                    }
                    ?>
                </div>
            </div>

        </div>

        <!-- Pagination -->
        <div class="row">
            <div class="col">
                <ul class="pagination d-flex justify-content-center mt-5">
                    <li class="page-item <?php if ($page - 1 == 0) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?= $page - 1; ?>">Previous</a>
                    </li>
                    <?php
                    for ($i = 1; $i <= $totalPages; $i++) {
                    ?>
                        <li class="page-item <?php if ($page == $i) echo 'active'; ?>">
                            <a class="page-link" href="?page=<?= $i; ?>"><?= $i; ?></a>
                        </li>
                    <?php
                    } ?>
                    <li class="page-item <?php if ($page + 1 > $totalPages) echo 'disabled'; ?>">
                        <a class="page-link" href="?page=<?= $page + 1; ?>">Next</a>
                    </li>
                </ul>
            </div>
        </div>

    </div>
<?php
}
?>

<?php
//}
?>