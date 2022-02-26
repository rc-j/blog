<?php
session_start();
include '../include/connection.php';
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
                    <ul class="ms-2 navbar-nav d-flex align-items-between flex-column">
                    <img class="ms-2 navbar-brand img-fluid rounded-circle" src="../assets/<?= $row['admin_photo']; ?>" alt="Admin Photo">
                    <li class="fs-3">Dashboard</li>
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
?>