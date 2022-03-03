<?php
session_start();
include '../include/connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['Username'];
    $password = $_POST['Password'];
    $confirmPassword = $_POST['Confirm_password'];
    $adminType = 0;
    if (empty($username) || empty($password) || empty($confirmPassword)) {
        echo '<div class="alert alert-danger">Field with * is required.</div>';
        exit();
    } elseif ($password != $confirmPassword) {
        echo '<div class="alert alert-danger">Confirmed password is not same as password</div>';
        exit();
    }
    $count = 0;
    $query = 'SELECT * FROM admins WHERE username = :username';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count > 0) {
        echo '<div class="alert alert-danger">Username already exists</div>';
        exit();
    }
    $userImage = $_FILES['Photo'];
    $image_name = $userImage['name']; //filename before upload: example.jpg
    $image_type = $userImage['type']; // eg: image/jpeg
    $image_temp = $userImage['tmp_name']; //tempLocation in server eg: /tmp/phpvbRcKv
    $image_error = $userImage['error']; //returns error code
    $image_extension_array = explode('.', $image_name);
    $image_extension = strtolower(end($image_extension_array));
    $image_size = $userImage['size'];
    $fileName = rand(0, 100000000) . '.' . $image_extension;
    $allowed_extensions = array('jpg', 'jpeg', 'png');
    if (!empty($image_name) && !in_array($image_extension, $allowed_extensions) || !($image_size < 2097152)) {
        echo '<div class="alert alert-danger">Photo must be less than 2MB<br>jpg, jpeg or png are only allowed.</div>';
        exit();
    } elseif (empty($image_name)) {
        $query = 'INSERT INTO admins(admin_type, username, password) VALUES (:admintype, :username, :password)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':admintype', $adminType);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->execute();
        $_SESSION['admin'] = $username;
        exit();
    } else {
        move_uploaded_file($image_temp, realpath(dirname(getcwd())) . '/assets/admins/' . $fileName);
        $query = 'INSERT INTO admins(admin_type, username, password, admin_photo) VALUES (:admin_type,:username,:password,:admin_photo)';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':admin_type', $adminType);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':admin_photo', $fileName);
        $stmt->execute();
        $_SESSION['admin'] = $username;
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
        <div class="position-fixed col-lg-3 col-xl-2 p-0">
            <?php
            include '../include/sidebar.php';
            ?>
        </div>
        <div style="position: absolute; z-index: -1;" class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10 p-0">
            <?php
            include '../include/posts.php';
            ?>
        </div>
    </div>
</div>
<script>
    "use strict"
    window.onload = (event) => {
        var toastElList = [].slice.call(document.querySelectorAll('.toast'))
        var toastList = toastElList.map(function(toastEl) {
            return new bootstrap.Toast(toastEl).show()
        })
    }
</script>
<div class="toast-container position-absolute bottom-0 end-0 p-3">
    <div class="bg-success toast" id="welcome" role="alert" data-bs-delay="3000">
        <div class="toast-header">
            <strong class="me-auto">Mini-facebook</strong>
            &nbsp;<small>just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body">
            Welcome, <?= $_SESSION['admin'] ?>!
        </div>
    </div>
</div>
<?php
include '../include/footer.php';
?>