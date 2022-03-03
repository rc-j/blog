<?php
session_start();
include '../include/connection.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['Username'];
  $password = $_POST['Password'];
  if (empty($username) || empty($password)) {
    $message = '<div class="alert alert-danger">Please enter data</div>';
    exit();
  } else {
    $count = 0;
    $query = 'SELECT * FROM admins WHERE username = :username AND password = :password';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count > 0) {
      $_SESSION['admin'] = $username;
      exit();
    } else {
      $message = '<div class="alert alert-danger">Data does not match</div>';
      echo $message;
      exit();
    }
  }
}
if (isset($_SESSION['admin'])) {
  header('Location: dashboard.php');
  exit();
}
include '../include/header.php';
?>
<a href="../index.php" class="btn btn-info">Home</a>
<div class="mt-3 accordion mx-auto text-center col-sm-9 col-md-6 col-lg-5 col-xl-4" id="accordionForm">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSignin">
        Already a member?
      </button>
    </h2>
    <div id="collapseSignin" class="accordion-collapse collapse show" data-bs-parent="#accordionForm">
      <div class="accordion-body">
        <form id="signinForm">
          <fieldset>
            <legend class="mb-3">Sign in</legend>
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="old-username" name="Username" placeholder="username">
              <label for="old-username">* Username</label>
            </div>
            <div class="form-floating mb-3">
              <input autocomplete="current-password" type="password" class="form-control" name="Password" id="old-password" placeholder="Password">
              <label for="old-password">* Password</label>
            </div>
            <div id="signin-validation"></div>
            <button class="btn btn-success d-grid col-7 mx-auto" type="submit" onclick="handleSignin(event)">Login</button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSignup">
        New user?
      </button>
    </h2>
    <div id="collapseSignup" class="accordion-collapse collapse" data-bs-parent="#accordionForm">
      <div class="accordion-body">
        <form id="signupForm">
          <fieldset>
            <legend class="mb-3">Sign up</legend>
            <div class="form-floating mb-3">
              <input type="text" class="form-control" id="new-username" name="Username" placeholder="16_chars_max.">
              <label for="new-username">* Choose Username</label>
            </div>
            <div class="form-floating mb-3">
              <input type="file" class="form-control" id="admin-pic" name="Photo" placeholder="Admin-Pic">
              <label for="admin-pic">Select your photo (Less than 2 MB)</label>
            </div>
            <div class="form-floating mb-3">
              <input autocomplete="new-password" type="password" name="Password" class="form-control" id="new-password" placeholder="New Password">
              <label for="new-password">* New Password</label>
            </div>
            <div class="form-floating mb-3">
              <input autocomplete="new-password" type="password" name="Confirm_password" class="form-control" id="new-password2" placeholder="New Password">
              <label for="new-password2">* Confirm Password</label>
            </div>
            <div id="signup-validation"></div>
            <button class="btn btn-primary d-grid col-7 mx-auto" type="submit" onclick="handleSignup(event)">Register</button>
          </fieldset>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  "use strict"
  const handleSignin = (event) => {
    event.preventDefault()
    const form = document.getElementById('signinForm')
    const formData = new FormData(form)
    let validation = ""
    for (let pair of formData.entries()) {
      if (pair[1] === "") {
        validation += `* ${pair[0]} must not be empty<br>`
      }
    }
    if (validation === "") {
      fetch('index.php', {
          method: 'POST',
          body: formData
        }).then(res => res.text())
        .then(msg => {
          if (msg === "") window.location.replace("dashboard.php")
          else document.getElementById("signin-validation").innerHTML = msg
        })
    } else {
      document.getElementById("signin-validation").innerHTML = `
        <div class="alert alert-danger">${validation}</div>
      `
    }
  }
  const handleSignup = (event) => {
    event.preventDefault()
    const form = document.getElementById('signupForm')
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
      fetch('dashboard.php', {
          method: 'POST',
          body: formData
        }).then(res => res.text())
        .then(msg => {
          if (msg === "") window.location.replace("dashboard.php")
          else document.getElementById("signup-validation").innerHTML = msg
        })
    } else {
      document.getElementById("signup-validation").innerHTML = `
        <div class="alert alert-danger">${validation}</div>
      `
    }
  }
</script>
<?php
include '../include/footer.php';
?>