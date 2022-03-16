<?php
session_start();
include '../include/connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $postID = $_POST['postID'];
    $comment = $_POST['Comment'];
    $query = 'INSERT INTO comments(user_id, post_id, comment) VALUES (:user_id, :post_id, :comment)';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':user_id', $_SESSION['admin']);
    $stmt->bindParam(':post_id', $postID);
    $stmt->bindParam(':comment', $comment);
    $stmt->execute();
    echo '<span class="text-capitalize fs-5">' . $_SESSION['admin'] . '</span> says: <strong class="fs-4">' . $comment . '</strong><span class="d-flex justify-content-end">just now</span>';
    exit();
}
include '../include/header.php';
$postID = $_GET['post_id'];
if (!isset($_GET['post_id'])) {
    header('Location: index.php');
    exit();
}
?>

<div class="container-fluid">
    <div class="row">
        <div class="col-lg-3 col-xl-2 p-0">
            <?php
            if (isset($_SESSION['admin'])) {
                include '../include/adminSidebar.php';
            } else {
                include '../include/sidebar.php';
            }
            ?>
        </div>

        <div class="col-lg-9 col-xl-10">
            <?php
            // Get Category Name
            $query = 'SELECT * FROM posts WHERE post_id = ?';
            $stmt = $db->prepare($query);
            $stmt->execute([$postID]);
            $row = $stmt->fetch();
            ?>

            <div class="card">
                <img src="/project/assets/<?= $row['picture']; ?>" class="card-img-top" alt="Picture not available">
                <div class="card-body">
                    <h5 class="card-title text-center fs-3"><?= $row['title']; ?></h5>
                    <p class="card-text fs-5"> <?= nl2br($row['content']) ?>
                    </p>
                </div>
                <?php
                $query = 'SELECT * FROM comments WHERE post_id = ?';
                $stmt = $db->prepare($query);
                $stmt->execute([$postID]);
                while ($row = $stmt->fetch()) {
                ?>
                    <div class="card-footer bg-secondary">
                        <?php echo '<span class="text-capitalize fs-5">' . $row['user_id'] . '</span> says: <strong class="fs-4">' . $row['comment'] . '</strong><span class="d-flex justify-content-end">on ' . $row['commented_on'] . '</span>'; ?>
                    </div>
                <?php
                }
                if (isset($_SESSION['admin'])) {
                ?>
                    <div id="comment-validation"></div>
                    <textarea placeholder="Add a comment... max. 500 characters" class="form-control" id="comment" rows="2" cols="45"></textarea>
                    <button type="button" onclick="handleComment(event)" value="<?= $_GET['post_id'] ?>" class="d-grid col-6 btn btn-primary mx-auto mt-2" id="commentButton">Add comment</button>
                <?php
                }
                ?>

            </div>
        </div>

    </div>
</div>

<script>
    const handleComment = (event) => {
        event.preventDefault()
        const formData = new FormData()
        let comment = document.getElementById("comment").value
        let postID = document.getElementById("commentButton").value
        if (comment !== "") {
            formData.append("Comment", comment)
            formData.append("postID", postID)
            fetch('post.php', {
                    method: 'POST',
                    body: formData
                }).then(res => res.text())
                .then(msg => document.getElementById("comment-validation").innerHTML = msg)
        } else {
            document.getElementById("comment-validation").innerHTML = `
        <div class="alert alert-danger">Comment is empty!</div>
      `
        }
    }
</script>
<?php
include '../include/footer.php';
?>