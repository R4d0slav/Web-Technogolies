<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL . "style.css" ?>">
<meta carset="UTF-8" />
<title>Description</title>


<h1 class="pageTitle">Description</h1>
<?php include("view/menu.php"); ?>
<form class="search" action="<?= BASE_URL . "search" ?>" method="get">
    <input type="text" name="query" autocomplete="off" required placeholder="Search.." />
    <button>Search</button>
</form>

<div id="main">
        <img id="descriptionImg" src="<?= $movie["img_url"] ?>">
        <div id="content"> 
            <h2 
            <?php if($_SESSION["user"]): ?>
            contenteditable
            <?php endif; ?>
            ><?= $movie["title"] ?></h2>
            <form id="description" action="" method="post">
                <label for="textarea">Description: </label><br/>
                <textarea id="descriptionText" name="textarea" 
                <?php if (empty($_SESSION["user"])): ?>
                    readonly
                <?php endif; ?>
                ><?= $movie["description"] ?></textarea>
            </form>
            <?php if (!empty($_SESSION["user"])): ?>
                <div id="buttons">
                <div>
                    <button id="favorite">Favorite</button>
                </div>

                <div>
                    <button id="delete">Delete</button>
                </div>
            </div>
            <?php endif; ?>
            <div id="year">
                <label for="year">Year:</label>
                <input id="iyear" type="number" name="year" value="<?= $movie["year"] ?>" style="background-color:transparent; color:white;"
                <?php if(empty($_SESSION["user"])): ?>
                readonly
                <?php endif; ?>
                />
            </div>
        </div>
</div>


<footer>
    <div id="footerArea">
        <h3>Movies/Tv-Shows Library</h3>
        <p>Add new Movies and Tv-Shows and keep track of what you have watched.</p>
    </div>
</footer>

<script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
<script type="text/javascript">
    $(document).ready(function() {

        $("#delete").on("click", function() {
            if(confirm("Are you sure?")){
                $.post("<?= BASE_URL . "movie/delete" ?>",
                {id: <?= $movie["id"] ?>},
                function() {
                    window.location.href = "../home";
                }
            );}
        });

        $("#iyear").on("blur", function() {
            let text = $(this).val();
            $.post("<?= BASE_URL . "update/movie-year" ?>",
                {id: <?= $movie["id"] ?>, data: text},
            );
        });
        
        $("h2").on("blur", function() {
            let text = $(this).text();
            $.post("<?= BASE_URL . "update/movie-title" ?>",
                {id: <?= $movie["id"] ?>, data: text}
            );
        })

        $("#descriptionText").on("change", function() {
            let text = $(this).val();     
            $.post("<?= BASE_URL . "update/movie-description" ?>", 
                { id: <?= $movie["id"] ?>, data: text }
            );
        });

        <?php if ($movie["favorite"]): ?>
            $("#favorite").css("background-color", "red");
        <?php else: ?>
            $("#favorite").css("background-color", "white");
        <?php endif; ?>


        $("#favorite").on("click", function() {
            $.post("<?= BASE_URL . "favorites/movie-update" ?>",
                { id: <?= $movie["id"] ?> },
                function() {
                    if ($("#favorite").css("background-color")=="rgb(255, 0, 0)") {
                        $("#favorite").css("background-color", "white");
                    } else {
                        $("#favorite").css("background-color", "red");
                    }
                }
            );
        });
    });
</script>