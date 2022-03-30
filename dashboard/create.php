<?php
session_start();
include '../include/connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $category = $_POST['Category'];
    $title = $_POST['Title'];
    $photo = $_FILES['Photo'];
    $content = $_POST['Content'];
    $confirmPassword = $_POST['Confirm_password'];
    if (empty($category) || empty($title) || empty($content)) {
        echo '<div class="alert alert-danger">Field with * is required.</div>';
        exit();
    }
    $image_name = $photo['name']; //filename before upload: example.jpg
    $image_type = $photo['type']; // eg: image/jpeg
    $image_temp = $photo['tmp_name']; //tempLocation in server eg: /tmp/phpvbRcKv
    $image_error = $photo['error']; //returns error code
    $image_extension_array = explode('.', $image_name);
    $image_extension = strtolower(end($image_extension_array));
    $image_size = $photo['size'];
    $fileName = rand(0, 100000000) . '.' . $image_extension;
    $allowed_extensions = array('jpg', 'jpeg', 'png');
    if (!empty($image_name) && !in_array($image_extension, $allowed_extensions) || !($image_size < 2097152)) {
        echo '<div class="alert alert-danger">Photo must be less than 2MB<br>jpg, jpeg or png are only allowed.</div>';
        exit();
    } elseif (empty($image_name)) {
        $query = 'INSERT INTO posts(author, category, title, content) VALUES (:author, :category, :title, :content)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':author', $_SESSION['admin']);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->execute();
        exit();
    } else {
        move_uploaded_file($image_temp, realpath(dirname(getcwd())) . '/assets/' . $fileName);
        $query = 'INSERT INTO posts(author, category, title, content, picture) VALUES (:author, :category, :title, :content, :picture)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':author', $_SESSION['admin']);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':title', $title);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':picture', $fileName);
        $stmt->execute();
        exit();
    }
}
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
            include '../include/adminSidebar.php';
            $query = 'SELECT * FROM categories ORDER BY category_id';
            ?>
        </div>
        <div class="col-lg-6">
            <form id="createPost">
                <fieldset>
                    <legend class="mt-3 mb-3 text-center">Create New Post</legend>
                    <div class="mb-3">
                        <select class="form-control btn btn-secondary" name="Category">
                            <option style="display:none;" selected value="">* Select category</option>
                            <?php
                            foreach ($db->query($query) as $row) {
                            ?>
                                <option value="<?= $row['category_name'] ?>"><?= $row['category_name'] ?></option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <div class="input-group">
                            <span class="input-group-text bg-secondary text-light">* Title</span>
                            <input class="form-control" type="text" name="Title" />
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="photo" class="form-label">Picture:</label>
                        <input class="form-control" type="file" id="photo" name="Photo" />
                    </div>
                    <div class="mb-3">
                        <label for="post_text">* Content:</label>
                        <textarea class="form-control" id="post_text" name="Content" rows="12"></textarea>
                    </div>
                    <div id="createPost-validation">
                    </div>
                    <button type="submit" class="btn btn-primary d-grid col-7 mx-auto" onclick="handlePost(event)">Publish</button>
                </fieldset>
            </form>
        </div>

    </div>
</div>
<script>
    "use strict"
    const handlePost = event => {
        event.preventDefault()
        const form = document.getElementById('createPost')
        const formData = new FormData(form)
        let validation = ""
        for (let pair of formData.entries()) {
            if (pair[1] === "") {
                validation += `* ${pair[0]} must not be empty<br>`
            }
        }
        if (validation === "") {
            fetch('create.php', {
                    method: 'POST',
                    body: formData
                }).then(res => res.text())
                .then(msg => {
                    if (msg === "") window.location.replace("dashboard.php")
                    else document.getElementById("createPost-validation").innerHTML = msg
                })
        } else {
            document.getElementById("createPost-validation").innerHTML = `
        <div class="alert alert-danger">${validation}</div>
      `
        }
    }
</script>

<?php
include '../include/footer.php';
?>