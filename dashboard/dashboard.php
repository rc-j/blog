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
        move_uploaded_file($image_temp, realpath(dirname(getcwd())) . '/assets/' . $fileName);
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
$query = 'SELECT admin_photo FROM admins WHERE username = :username';
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $_SESSION['admin']);
$stmt->execute();
$row = $stmt->fetch();
?>
<div class="container-fluid p-0">
    <div class="row">
        <div class="col-md-3 col-xl-2">
            <nav class="navbar navbar-expand-md navbar-dark bg-dark text-light">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#dashmenu">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="dashmenu" style="height: 670px;">
                    <ul class="ms-2 navbar-nav flex-column">
                        <img class="ms-2 navbar-brand img-fluid rounded-circle" src="../assets/<?= $row['admin_photo']; ?>" alt="Admin Photo">
                        <li class="fs-3 text-center">Dashboard</li>
                        <hr>
                        <li class="nav-item" data-bs-toggle="collapse" data-bs-target="#subDashMenu">
                            <a class="nav-link dropdown-toggle" href="#" role="button">
                                Posts
                            </a>
                        </li>
                        <ul class="collapse list-unstyled nav-item" id="subDashMenu">
                            <li><a class="nav-link" href="#">New Post</a></li>
                            <li><a class="nav-link" href="#">My Posts</a></li>
                        </ul>
                        <li class="nav-item">
                            <a href="#" class="nav-link">Messages</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Settings</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Categories</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/">Visit site</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
        <div class="col-2">
            <?php echo $_SESSION['admin']; ?>
            <a href="logout.php" class="btn btn-danger">Logout</a>

        </div>
    </div>
</div>

<?php
include '../include/footer.php';
?> */