<?php
if (isset($_SESSION['failedSignup'])) {
  $failedSignup = $_SESSION['failedSignup'];
  unset($_SESSION['failedSignup']);
}
?>

<?php require_once 'app/views/templates/headerPublic.php'?>
<main role="main" class="container">
  <div class="page-header" id="banner">
    <div class="row">
      <div class="col-lg-12">
        <h1>Create your account</h1>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-sm-4">
      <?php if (isset($failedSignup)): ?>
        <div class="alert alert-danger" role="alert">
          <?php echo $failedSignup; ?>
        </div>
      <?php endif; ?>
        
      <form action="/signup/create" method="post" class="my-3">
        <fieldset>
          <div class="form-group">
            <label for="username">Username</label>
            <input required type="text" class="form-control" name="username">
          </div>
          <div class="form-group">
            <label for="password">Password</label>
            <input required type="password" class="form-control" name="password">
          </div>
          <div class="form-group">
            <label for="password_confirm">Confirm Password</label>
            <input required type="password" class="form-control" name="password_confirm">
            <p class="form-text">Passwords should contain at least one uppercase character, one lowercase character, one numeric digit and one of the following special characters: !@#$&</p>
          </div>
          <br>
          <button type="submit" class="btn btn-primary">Sign up</button> 
          &nbsp;
          Already have an account? <a href="/login">Log in</a>.
        </fieldset>
      </form> 
    </div>
  </div>
<?php require_once 'app/views/templates/footer.php' ?>