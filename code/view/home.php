<!DOCTYPE html>

<link rel="stylesheet" type="text/css" href="<?= ASSETS_URL . "style.css" ?>">
<meta carset="UTF-8" />
<title>Home</title>


<h1 class="pageTitle">Welcome <?= $_SESSION["user"]; ?></h1>
<?php include("view/menu.php"); ?>
<form class="search" action="<?= BASE_URL . "search" ?>" method="get">
    <input type="text" name="query" autocomplete="off" required placeholder="Search.." />
    <button>Search</button>
</form>

<div id="main">
    <div id="live">
        <div id="liveSearch">
            <label for="search-field">Live Search</label><br/>
            <input id="search-field" type="text" name="query" autocomplete="off" autofocus placeholder="Live search..">
        </div>
        <div>
            <h2>Movies</h2>
        </div>
        <div class="imgContent"></div>
        <div>
            <h2>Tv-Shows</h2>
        </div>
        <div class="imgContent"></div>
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

        $("#search-field").keyup(function() {

            let query = $(this).val();
            $.get("<?= BASE_URL . "api/search?query=" ?>"+query, function(data) {
                $(".imgContent").first().children().remove();
                $(".imgContent").last().children().remove();
                for (item of ["movie", "tvShow"]) {
                    if (item in data) {
                        for (movie of data[item]) {
                            let url = window.location.href.substr(0, window.location.href.lastIndexOf("/"))+"?id="+movie["id"];
                            let form = document.createElement("form");
                            form.setAttribute("method", "post");
                            form.setAttribute("action", "<?= BASE_URL ?>"+item+"/description");

                            let id = document.createElement("input");
                            id.setAttribute("type", "hidden");
                            id.setAttribute("name", item+"_id");
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

                            form.append(document.createElement("br"));
                            form.append(id);
                            form.append(link);
                            form.append(p);

                            if (item == "movie") {
                                $(".imgContent").first().append(form);
                            } else {
                                $(".imgContent").last().append(form);
                            }
                        }
                    }
                }

            });

        });

    });

</script>
