<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header('Location: index.php');
    exit();
}
include '../include/connection.php';
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
                // Fetch users
                isset($_GET['page']) ? $page = $_GET['page'] : $page = 1;
                $limit = 6;
                $start = ($page - 1) * $limit;
                $query = 'SELECT * FROM admins LIMIT ' . $start . ', ' . $limit;
                $stmt = $db->query($query);
                $totalPages = ceil($stmt->rowCount() / $limit);
                $colors = ['dark', 'secondary', 'success', 'danger', 'info', 'primary'];
                $count = 1;
                while ($row = $stmt->fetch()) {
                ?>
                    <li class="list-group-item bg-<?= $colors[$count++ % 6] ?>"><?= $row['username'] . ' joined on ' . $row['joined_on']; ?>
                        <?php
                        echo $row['username'] != $_SESSION['admin'] ? '<a href="#" class="float-end btn btn-warning" data-bs-toggle="offcanvas" data-bs-whatever="' . $row['username'] . '" data-bs-target="#sendMessage">Send Message</a>' : '';
                        $query = 'SELECT * FROM admins WHERE username = :username';
                        $st = $db->prepare($query);
                        $st->bindParam(':username', $_SESSION['admin']);
                        $st->execute();
                        if ($st->fetch()['admin_type'] == 1) {
                            echo $row['admin_type'] == 0 ? '<i class="fa-solid fa-trash-can ms-3"></i>' : '';
                        };
                        ?>
                    </li>
                    <div class="offcanvas offcanvas-end" id="sendMessage">
                        <div class="offcanvas-header">
                            <h5 class="offcanvas-title" id="exampleLabel">New message</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
                        </div>
                        <div class="offcanvas-body">
                            <form id="messageForm">
                                <div class="mb-3">
                                    <label for="recipient-name" class="col-form-label">* Recipient:</label>
                                    <input type="text" class="form-control" name="Recipient" id="recipient-name">
                                </div>
                                <div class="mb-3">
                                    <label for="message-text" class="col-form-label">* Message:</label>
                                    <textarea class="form-control" name="Message" id="message-text"></textarea>
                                </div>
                                <div id="message-validation"></div>
                            </form>
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="offcanvas">Close</button>
                            <button type="button" class="btn btn-primary" onclick="handleMessage(event)">Send message</button>
                        </div>
                    </div>
                <?php
                }
                ?>
            </ul>
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
    <script>
        const handleMessage = (event) => {
            const form = document.getElementById('messageForm')
            const formData = new FormData(form)
            let validation = ""
            for (let pair of formData.entries()) {
                if (pair[1] === "") {
                    validation += `* ${pair[0]} must not be empty<br>`
                }
            }
            if (validation === "") {
                fetch('messages.php', {
                        method: 'POST',
                        body: formData
                    }).then(res => res.text())
                    .then(msg => document.getElementById("message-validation").innerHTML = msg)
            } else {
                document.getElementById("message-validation").innerHTML = `
        <div class="alert alert-danger">${validation}</div>
      `
            }

        }
        var myOffcanvasEl = document.getElementById('sendMessage')
        myOffcanvasEl.addEventListener('show.bs.offcanvas', function(event) {
            var button = event.relatedTarget
            var recipient = button.getAttribute('data-bs-whatever')
            var offcanvasBodyInput = myOffcanvasEl.querySelector('.offcanvas-body input')
            offcanvasBodyInput.value = recipient
        })
    </script>
    <?php
    include '../include/footer.php';
    ?>