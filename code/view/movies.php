<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL . "style.css" ?>">
<meta carset="UTF-8" />
<title>Movies</title>


<h1 class="pageTitle">Movies</h1>
<?php include("view/menu.php"); ?>
<form class="search" action="<?= BASE_URL . "search" ?>" method="get">
    <input type="text" name="query" autocomplete="off" required placeholder="Search.." />
    <button>Search</button>
</form>


<div id="main">
    <?php if (!empty($_SESSION["user"])): ?>
        <div>
            <div id="addContent">
                <h3>Add a Movie</h3>
                <input type="hidden" name="user_id" value="<?= $_SESSION["user_id"] ?>" />
                <p><input id="titleContent" type="text" name="title" required autocomplete="off" placeholder="Title.." /></p>
                <p><input id="img_urlContent" type="text" name="img_url" required autocomplete="off" placeholder="Image url.." /></p>
                <p><button onclick="addContent()">Add</botton></p>
            </div>
        </div>
    <?php endif; ?>
    <div class="imgContent">
        <?php foreach ($movies as $movie): ?>
            <form action="<?= BASE_URL . "movie/description" ?>" method="post">
                <input type="hidden" name="movie_id" value="<?= $movie["id"] ?>" />
                <a href="javascript:{}" onclick="this.closest('form').submit();return false;"><img src="<?= $movie["img_url"] ?>" alt="<?= $movie["title"] ?>"/></a>
                <p><?= $movie["title"] ?><br/><br/>
                <?php if (!is_null($movie["year"])): ?>
                     <?= "(" . $movie["year"] . ")" ?>
                <?php endif; ?></p>
            </form>
        <?php endforeach; ?>
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
        boxSlide();
    });


    function addContent() {

        $(".span").remove();
        if (!$("#titleContent").val()) {
            $('<p class="span"><span class="important">Title field cannot be empty!</span></p>').insertAfter($("#titleContent"));
        }
        if (!$("#img_urlContent").val()) {
            $('<p class="span"><span class="important">Url field cannot be empty!</span></p>').insertAfter($("#img_urlContent"));
        }

        if ($("#titleContent").val() && $("#img_urlContent").val()) {
            if($("#img_urlContent").val().length>1024) {
                $('<p class="span"><span class="important">Url too big!</span></p>').insertAfter($("#img_urlContent"));
                return;
            }
            $.post("<?= BASE_URL . "add/movie" ?>", 
                {title: $("#titleContent").val(), img_url: $("#img_urlContent").val()},
                function(movie) {
                    let url = window.location.href.substr(0, window.location.href.lastIndexOf("/"))+"?id="+movie["id"];
                    let form = document.createElement("form");
                    form.setAttribute("method", "post");
                    form.setAttribute("action", "<?= BASE_URL . "movie/description" ?>");

                    let id = document.createElement("input");
                    id.setAttribute("type", "hidden");
                    id.setAttribute("name", "movie_id");
                    id.setAttribute("value", movie["id"]);

                    let link = document.createElement("a");
                    link.setAttribute("href", "javascript:{}");
                    link.setAttribute("onclick", "this.closest('form').submit();return false;");
                    
                    let img = document.createElement("img");
                    img.setAttribute("src", movie["img_url"]);
                    img.setAttribute("alt", movie["title"]);

                    let p = document.createElement("p");
                    p.append(movie["title"]);

                    if (movie["year"]) {
                        p.append(document.createElement("br"));
                        p.append(document.createElement("br"));
                        p.append("("+movie["year"]+")");
                    }

                    link.append(img);

                    form.append(id);
                    form.append(link);
                    form.append(p);

                    $(".imgContent").append(form);   
                }
            )
            $("#titleContent").val("");
            $("#img_urlContent").val("");
        }
    }
     

    function boxSlide() {
    if (window.innerWidth<1200) {
        return;
    }
    let boxWidth = $("#addContent").width();

    $("#addContent").on("mouseenter", function() {
        $("#addContent").animate({ width: boxWidth*2.5 }, 200);
    }).on("mouseleave", function() {
        $("#addContent").animate({ width: boxWidth }, 200);
    });
}

</script>
