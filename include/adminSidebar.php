<?php
$query = 'SELECT admin_photo FROM admins WHERE username = :username';
$stmt = $db->prepare($query);
$stmt->bindParam(':username', $_SESSION['admin']);
$stmt->execute();
$row = $stmt->fetch();
?>
<nav class="sticky-top navbar navbar-expand-lg bg-dark navbar-dark">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="sidebar">
        <ul class="navbar-nav flex-column align-self-start" style="height: 100vh; overflow: auto;">
            <a class="nav-link text-center" href="dashboard.php">
                <li class="nav-item">
                    <img class="img-fluid rounded-circle" src="../assets/admins/<?= $row['admin_photo'] ?>" alt="Admin Photo">
                </li>
                <li class="nav-item mt-3 text-center fs-4">
                    Dashboard
                </li>
            </a>
            <li class="nav-item ms-3">
                <a class="nav-link" href="profile.php">
                    <i class="fa-solid fa-user me-2"></i>
                    Profile</a>
            </li>
            <li class="nav-item ms-3">
                <a class="nav-link" data-bs-toggle="collapse" data-bs-target="#subSidebar" href="#">
                    <i class="fa-solid fa-pen me-2"></i>
                    Posts</a>
            </li>
            <ul class="collapse" id="subSidebar">
                <li><a class="nav-link" href="#"><i class="fa-solid fa-pen-to-square me-2"></i>Create new</a></li>
                <li><a class="nav-link" href="#"><i class="fa-solid fa-table-cells-large me-2"></i>My Posts</a></li>
            </ul>
            <li class="nav-item ms-3">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-message me-2"></i>Messages</a>
            </li>
            <li class="nav-item ms-3">
                <a class="nav-link" href="#">
                    <i class="fa-solid fa-user-group me-2"></i>All users</a>
            </li>
            <li class="nav-item ms-3 mb-5">
                <a class="nav-link" href="logout.php">
                    <i class="fa-solid fa-right-from-bracket me-2"></i>Sign out</a>
            </li>
        </ul>
    </div>
</nav>