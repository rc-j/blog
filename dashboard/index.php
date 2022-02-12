<?php
session_start();
include '../include/connection.php';
include '../include/header.php';
if (isset($_SESSION['admin'])) {
  header('Location: dashboard.php');
  exit();
}
?>
<!-- Check for requests -->
<!-- Contents -->
<!-- Check for input data -->
<div class="accordion text-center col-md-6 offset-md-3 col-lg-4 offset-lg-4" id="accordionForm">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSignin">
        Already a member?
      </button>
    </h2>
    <div id="collapseSignin" class="accordion-collapse collapse show" data-bs-parent="#accordionForm">
      <div class="accordion-body">
        <form id="signinForm" class="d-flex align-items-center flex-column">
          <div class="card card-body">
            <fieldset>
              <legend class="mb-3">Sign in</legend>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="old-username" placeholder="Username">
                <label for="old-username">Username</label>
              </div>
              <div class="form-floating mb-3">
                <input autocomplete="current-password" type="password" class="form-control" id="old-password" placeholder="Password">
                <label for="old-password">Password</label>
              </div>
              <button class="btn btn-success" type="button" onclick="handleSignin(event)">Login</button>
            </fieldset>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="accordion-item">
    <h2 class="accordion-header">
      <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSignup">
        New user?
      </button>
    </h2>
    <div id="collapseSignup" class="accordion-collapse collapse" data-bs-parent="#accordionForm">
      <div class="accordion-body">
        <form id="signupForm" class="d-flex align-items-center flex-column">
          <div class="card card-body">
            <fieldset>
              <legend class="mb-3">Sign up</legend>
              <div class="form-floating mb-3">
                <input type="text" class="form-control" id="new-username" placeholder="16 chars max.">
                <label for="new-username">Choose Username</label>
              </div>
              <div class="form-floating mb-3">
                <input type="file" class="form-control" id="admin-pic" placeholder="Admin-Pic">
                <label for="admin-pic">Select your photo</label>
              </div>
              <div class="form-floating mb-3">
                <input autocomplete="new-password" type="password" class="form-control" id="new-password" placeholder="New Password">
                <label for="new-password">New Password</label>
              </div>
              <div class="form-floating mb-3">
                <input autocomplete="new-password" type="password" class="form-control" id="new-password2" placeholder="New Password">
                <label for="new-password2">Confirm Password</label>
              </div>
              <button class="btn btn-primary" type="button" onclick="handleSignup(event)">Register</button>
            </fieldset>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  const handleSignin = (event) => {
    event.preventDefault()
    console.log("User Signed in")
  }
  const handleSignup = (event) => {
    event.preventDefault()
    console.log("User Signed up")
  }
</script>

<!-- Sidebar -->
<!-- Pagination -->
<?php
include '../include/footer.php';
?>