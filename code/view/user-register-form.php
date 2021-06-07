<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL . "style.css" ?>">
<meta charset="UTF-8" />
<title>Register form</title>

<h1 class="pageTitle">Register</h1>
<?php include("view/menu.php"); ?>
<form class="search" action="<?= BASE_URL . "home" ?>" method="get">
    <input type="text" name="query" autocomplete="off" required placeholder="Search.." />
    <button>Search</button>
</form>

<div id="main">
    <form id="registerForm" action="<?= BASE_URL . "user/register" ?>" method="post">
        <h2>Register form</h2>
        <div id="first">
            <p id="firstname">
                <input type="text" name="firstname" autocomplete="off" placeholder="First Name" required />
                <span class="important"><?= $errors["firstname"] ?></span>
            </p>
            <p id="lastname">
                <input type="text" name="lastname" autocomplete="off" placeholder="Last Name" required />
                <span class="important"><?= $errors["lastname"] ?></span>
            </p>
        </div>
        <div id="last">
            <p>
                <input type="text" name="username" autocomplete="off" placeholder="Username" required />
                <span class="important"><?= $errors["username"] ?></span>
            </p>
            <p>
                <input type="text" name="email" autocomplete="off" placeholder="Email" required />
                <span class="important"><?= $errors["email"] ?></span>
            </p>
            <p>
                <input type="password" name="password" placeholder="Password" required />
                <span class="important"><?= $errors["password"] ?></span>
            </p>
            </p>
                <input type="password" name="confirmpassowrd" placeholder="Confirm Password" required />
                <span class="important"><?= $errors["confirmpassword"] ?></span>
            </p>
        </div>
        <p><button>Register Now</button></p>
    </form>
</div>

<footer>
    <div id="footerArea">
        <h3>Movies/Tv-Shows Library</h3>
        <p>Add new Movies and Tv-Shows and keep track of what you have watched.</p>
    </div>
</footer>