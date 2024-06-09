<?php
	$lockedOut = false;

	// Check if user is locked out
	if (isset($_SESSION['lockoutUntil'])) {
		$lockoutUntil = $_SESSION['lockoutUntil'];

		// Check if lockout time has passed; if not, we're still locked out.
		// If time has passed, we can reset the lockout time and attempts counter
		if ($lockoutUntil > time()) {
			$lockedOut = true;
		} else {
			$lockedOut = false;
			unset($_SESSION['lockoutUntil']);
			unset($_SESSION['failedAttempts']);
		}
	}

	if (isset($_SESSION['signupSuccess'])) {
		$signupSuccess = $_SESSION['signupSuccess'];
		unset($_SESSION['signupSuccess']);
	}
?>

<?php require_once 'app/views/templates/headerPublic.php'?>
<main role="main" class="container">
	<?php if (isset($_SESSION['failedAttempts']) || isset($_SESSION['invalidLogin'])): ?>
		<div class="alert alert-warning" role="alert">
			Invalid login. Please try again.
		</div>
	<?php endif; ?>

	
	<?php if ($lockedOut): ?>
		<div class="alert alert-danger" role="alert">
		You are locked out. Please try again in <?= $lockoutUntil - time() ?> seconds.
		</div>
	<?php endif; ?>

	<?php if ($signupSuccess): ?>
		<div class="alert alert-success" role="alert">
			Account successfully created! Please log in below.
		</div>
	<?php endif; ?>
	
	<div class="page-header" id="banner">
		<div class="row">
			<div class="col-lg-12">
				<h1>Login</h1>
			</div>
		</div>
	</div>

	<div class="row">
			<div class="col-sm-4">
				<form action="/login/verify" method="post" class="my-3">
					<fieldset>
						<div class="form-group">
							<label for="username">Username</label>
							<input required type="text" class="form-control" name="username">
						</div>
						<div class="form-group">
							<label for="password">Password</label>
							<input required type="password" class="form-control" name="password">
						</div>
						<br>
						<button type="submit" class="btn btn-primary" <?= $lockedOut ? 'disabled' : '' ?>>Log in</button>
						&nbsp;
						Don't have an account? <a href="/signup">Sign up</a>.
					</fieldset>
				</form> 
			</div>
	</div>
<?php require_once 'app/views/templates/footer.php' ?>

<!-- failedAttempts: <?= $_SESSION['failedAttempts'] ?> -->
<!-- Locked out? <?= $lockedOut ? 'true' : 'false' ?> -->
<!-- lockoutUntil: <?= $_SESSION['lockoutUntil'] ?> -->
