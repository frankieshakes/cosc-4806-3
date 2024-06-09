<?php require_once 'app/views/templates/header.php' ?>
<div class="container">
    <div class="page-header" id="banner">
        <div class="row">
            <div class="col-lg-12">
                <h1>Welcome back, <?= $_SESSION['username'] ?>!</h1>
                <p class="lead"> <?= date("F jS, Y"); ?></p>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <p> <a href="/logout">Click here to log out</a></p>
        </div>
    </div>

    <?php require_once 'app/views/templates/footer.php' ?>
