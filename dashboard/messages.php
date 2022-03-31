<?php
session_start();
include '../include/connection.php';
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $sentTo = $_POST['Recipient'];
    $message = $_POST['Message'];
    if (empty($sentTo) || empty($message)) {
        $msg = '<div class="alert alert-danger">Please enter all data</div>';
        exit();
    } else {
        $count = 0;
        $query = 'SELECT * FROM admins WHERE username = :username';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':username', $sentTo);
        $stmt->execute();
        $count = $stmt->rowCount();
        if ($count > 0) {
            $query = 'INSERT INTO messages(sent_by, sent_to, message) VALUES (:sent_by, :sent_to, :message)';
            $stmt = $db->prepare($query);
            $stmt->bindParam(':sent_by', $_SESSION['admin']);
            $stmt->bindParam(':sent_to', $sentTo);
            $stmt->bindParam(':message', $message);
            $stmt->execute();
            $msg = '<div class="alert alert-success">Message sent successfully.</div>';
        } else {
            $msg = '<div class="alert alert-danger">The recipient username not found.</div>';
        }
        echo $msg;
        exit();
    }
}
include '../include/header.php';
?>
<div class="container-fluid">
    <div class="row">
        <div class="position-fixed col-lg-3 col-xl-2 p-0">
            <?php
            include '../include/adminSidebar.php';
            ?>
        </div>
        <div style="position: absolute; z-index: -1;" class="offset-lg-3 offset-xl-2 col-lg-9 col-xl-10 p-0">
            <ul class="list-group">
                <?php
                // Fetch messages
                $query = 'SELECT * FROM messages WHERE sent_to = :sent_to ORDER BY message_id DESC';
                $stmt = $db->prepare($query);
                $stmt->bindParam(':sent_to', $_SESSION['admin']);
                $stmt->execute();
                echo $stmt->rowCount() == 0 ? '<div class="alert alert-warning">You have no messages.</div>' : '<h3 class="bg-warning text-center">All messages</h3>';
                $colors = ['dark', 'info', 'secondary', 'success', 'danger', 'primary'];
                $count = 1;
                while ($row = $stmt->fetch()) {
                ?>

                    <li class="list-group-item bg-<?= $colors[$count++ % 6] ?>"><?= '<span class="fs-3">' . $row['message'] . '</span>' . '<a href="#"><i class="fa-solid fa-trash-can ms-3"></i></a><span class="float-end">by <strong>' . $row['sent_by'] . '</strong> on ' . $row['sent_on'] . '</span>'; ?></li>

                <?php
                }
                ?>
            </ul>
        </div>
    </div>
    <?php
    include '../include/footer.php';
    ?>