<?php
	$lockedOut = false;

	if (isset($_SESSION['signupSuccess'])) {
		$signupSuccess = $_SESSION['signupSuccess'];
		unset($_SESSION['signupSuccess']);
	}

	if (isset($_SESSION['lockoutUntil'])) {
		$lockoutUntil = $_SESSION['lockoutUntil'];
		if ($lockoutUntil > time()) {
			$lockedOut = true;
		} else {
			$lockedOut = false;
			unset($_SESSION['lockoutUntil']);
			unset($_SESSION['failedAttempts']);
		}
	}
?>

<?php require_once 'app/views/templates/headerPublic.php'?>
<main role="main" class="container">
	<?php if (isset($signupSuccess)): ?>
		<div class="alert alert-success" role="alert">
			Account successfully created! Please log in below.
		</div>
	<?php endif; ?>

	<?php if ($lockedOut): ?>
		<div class="alert alert-danger" role="alert">
		<p>You are locked out. Please try again in <?= $lockoutUntil - time() ?> seconds.</p>
		</div>
	<?php endif; ?>

	
	<div class="page-header" id="banner">
		<div class="row">
			<div class="col-lg-12">
					<h1>You are not logged in</h1>
					<p>failedAttempts: <?= $_SESSION['failedAttempts'] ?></p>
					<p>lockoutUntil: <?= $_SESSION['lockoutUntil'] ?></p>
					<p>Now? <?= time() ?></p>
					<p>Locked out? <?= $lockedOut ? 'true' : 'false' ?></p>
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
						<button type="submit" class="btn btn-primary" <?= $lockedOut ? 'disabled' : '' ?>>Login</button> 
						Don't have an account? <a href="/signup">Sign up</a>.
					</fieldset>
				</form> 
			</div>
	</div>
<?php require_once 'app/views/templates/footer.php' ?>