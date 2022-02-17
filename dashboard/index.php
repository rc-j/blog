<?php
session_start();
include '../include/connection.php';
include '../include/header.php';
if (isset($_SESSION['admin'])) {
  header('Location: dashboard.php');
  exit();
}
?>

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
              <input type="text" class="form-control" id="old-username" name="username" placeholder="Username">
              <label for="old-username">* Username</label>
            </div>
            <div class="form-floating mb-3">
              <input autocomplete="current-password" type="password" class="form-control" name="password" id="old-password" placeholder="Password">
              <label for="old-password">* Password</label>
            </div>
            <div id="signin-validation" class="text-uppercase">
            </div>
            <button class="btn btn-success d-grid col-7 mx-auto" type="button" onclick="handleSignin(event)">Login</button>
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
              <input type="text" class="form-control" id="new-username" name="username" placeholder="16_chars_max.">
              <label for="new-username">* Choose Username</label>
            </div>
            <div class="form-floating mb-3">
              <input type="file" class="form-control" id="admin-pic" name="photo" placeholder="Admin-Pic">
              <label for="admin-pic">Select your photo</label>
            </div>
            <div class="form-floating mb-3">
              <input autocomplete="new-password" type="password" name="password" class="form-control" id="new-password" placeholder="New Password">
              <label for="new-password">* New Password</label>
            </div>
            <div class="form-floating mb-3">
              <input autocomplete="new-password" type="password" name="confirm_password" class="form-control" id="new-password2" placeholder="New Password">
              <label for="new-password2">* Confirm Password</label>
            </div>
            <div id="signup-validation" class="text-uppercase"></div>
            <button class="btn btn-primary d-grid col-7 mx-auto" type="button" onclick="handleSignup(event)">Register</button>
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
      fetch('/index.php', {
        method: 'POST',
        body: formData
      }).then(res => console.log(res.json))
    } else {
      document.getElementById("signin-validation").innerHTML = `
        <div class="alert alert-danger d-flex align-items-center" role="alert">
        <div>${validation}</div></div>
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
    if (formData.get('password') !== formData.get('confirm_password')) {
      validation += `* confirmed password is not same as password<br>`
    }

    if (validation === "") {
      fetch('/signup.php', {
        method: 'POST',
        body: formData
      }).then(res => console.log(res.json))
    } else {
      document.getElementById("signup-validation").innerHTML = `
        <div class="alert alert-danger d-flex align-items-center" role="alert">
        <div>${validation}</div></div>
      `
    }
  }
</script>
<?php
// signin request not working
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];
  echo "$username";
  if (empty($username) || empty($password)) {
    $message = '<div class="alert alert-danger">Please enter data</div>';
  } else {
    $query = 'SELECT * FROM admins WHERE admin_username = :username AND admin_password = :password';
    $stmt = $db->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();
    $count = $stmt->rowCount();
    if ($count > 0) {
      $_SESSION['admin'] = $username;
      header('Location:dashboard.php');
      exit();
    } else {
      $message = '<div class="alert alert-danger">Data doesnt match</div>';
    }
  }
  if (isset($message)) echo $message;
}

include '../include/footer.php';
?>