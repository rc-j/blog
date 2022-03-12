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
    if ($count > 0 && $username != $_SESSION['admin']) {
        echo '<div class="alert alert-danger">Username already exists</div>';
        exit();
    }
    $query = 'SELECT admin_photo FROM admins WHERE username = :username';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $_SESSION['admin']);
    $stmt->execute();
    $row = $stmt->fetch();
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
        $query = 'UPDATE admins SET admin_type = :admintype, username = :username, password = :password WHERE username = :oldUsername';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':admintype', $adminType);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':oldUsername', $_SESSION['admin']);
        $stmt->execute();
        $_SESSION['admin'] = $username;
        echo '<div class="alert alert-success">Profile updated successfully.</div>';
        exit();
    } else {
        move_uploaded_file($image_temp, realpath(dirname(getcwd())) . '/assets/admins/' . $fileName);
        unlink(realpath(dirname(getcwd())) . "/assets/admins/" . $row['admin_photo']);
        $query = 'UPDATE admins SET admin_type = :admintype, username = :username, password = :password, admin_photo = :adminphoto WHERE username = :oldUsername';
        $stmt = $db->prepare($query);
        $stmt->bindParam(':admintype', $adminType);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':adminphoto', $fileName);
        $stmt->bindParam(':oldUsername', $_SESSION['admin']);
        $stmt->execute();
        $_SESSION['admin'] = $username;
        echo '<div class="alert alert-success">Profile updated successfully.</div>';
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
            ?>
        </div>

        <div class="col-auto">
            <form id="profileEditForm">
                <fieldset class="mt-3">
                    <legend class="mb-3 text-center">Edit profile</legend>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="new-username" name="Username" placeholder="16_chars_max.">
                        <label for="new-username">* Choose Username</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="file" class="form-control" id="admin-pic" name="Photo" placeholder="Admin-Pic">
                        <label for="admin-pic">Select your new photo (Less than 2 MB)</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input autocomplete="new-password" type="password" name="Password" class="form-control" id="new-password" placeholder="New Password">
                        <label for="new-password">* New Password</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input autocomplete="new-password" type="password" name="Confirm_password" class="form-control" id="new-password2" placeholder="New Password">
                        <label for="new-password2">* Confirm Password</label>
                    </div>
                    <div id="profile-validation"></div>
                    <button class="btn btn-success d-grid col-7 mx-auto" type="submit" onclick="handleProfileEdit(event)">Done</button>
                </fieldset>
            </form>
        </div>

    </div>
</div>
<script>
    "use strict"
    const handleProfileEdit = (event) => {
        event.preventDefault()
        const form = document.getElementById('profileEditForm')
        const formData = new FormData(form)
        let validation = ""
        for (let pair of formData.entries()) {
            if (pair[0] !== "photo" && pair[1] == "") {
                validation += `* ${pair[0]} must not be empty<br>`
            }
        }
        if (formData.get('Password') !== formData.get('Confirm_password')) {
            validation += `* Confirmed password is not same as password<br>`
        }
        if (validation === "") {
            fetch('profile.php', {
                    method: 'POST',
                    body: formData
                }).then(res => res.text())
                .then(msg => document.getElementById("profile-validation").innerHTML = msg)
        } else {
            document.getElementById("profile-validation").innerHTML = `
        <div class="alert alert-danger">${validation}</div>
      `
        }
    }
</script>
<?php
include '../include/footer.php';
?>