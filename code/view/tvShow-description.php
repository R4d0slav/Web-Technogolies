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

    <img id="descriptionImg" src="<?= $tvShow["img_url"] ?>">


    <div id="content"> 
        <h2 
        <?php if($_SESSION["user"]): ?>
            contenteditable
        <?php endif; ?>
        ><?= $tvShow["title"] ?></h2>

        
        <form id="description" action="" method="post">
            <label for="textarea">Description: </label><br/>
            <textarea id="descriptionText" name="textarea" 
            <?php if (empty($_SESSION["user"])): ?>
                readonly
            <?php endif; ?>
            ><?= $tvShow["description"] ?></textarea>
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
                <input id="iyear" type="number" name="year" value="<?= $tvShow["year"] ?>" style="background-color:transparent; color:white;" 
                <?php if(empty($_SESSION["user"])): ?>
                readonly
                <?php endif; ?>
                />
            </div>
    </div>

    
    <div id="addEpisodes">
        <?php if (!empty($_SESSION["user"])): ?>

            <div id="addDiv">
                <p for="episode">Add an episode by typing "Season"-"Episode"</p>
                <input type="hidden" name="tvShow_id" value="<?= $tvShow["id"] ?>"/>
                <input id="einput" type="text" name="episode" autocomplete="off" placeholder="Eg.: 1-Plot"/>
                <button id="add" name="add">Add</button>
                <button id="remove" name="remove">Remove</button>
            </div>

        <?php endif; ?>

        <div>
        <?php foreach($content as $key => $season): ?>
            <?php if(count($season)>0): ?>
            <div class="<?= "season" . $key+1 ?>"><p class="p" style="font-size:150%">Season <?= $key+1 ?></p>
            <?php foreach($season as $episode): ?>
                <p hidden style="color: silver; margin-left: 20px; overflow-wrap: break-word;"><?= $episode["episode"] ?>
                <?php if($_SESSION["user"]): ?>
                <button class="radio" name="<?= $episode["episode"] ?>" value="<?= $episode["watched"] ?>" 
                <?php if($episode["watched"]): ?>
                    style="background-color: green;"
                <?php endif; ?>
                >✓</button></p>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
            <?php endif; ?>
        <?php endforeach; ?>
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
                $.post("<?= BASE_URL . "tvShow/delete" ?>",
                {id: <?= $tvShow["id"] ?>},
                function() {
                    window.location.href = "../home";
                }
            );}
        });

       
        $("#iyear").on("blur", function() {
            let text = $(this).val();
            $.post("<?= BASE_URL . "update/tvShow-year" ?>",
                {id: <?= $tvShow["id"] ?>, data: text}
            );
        });

        $("h2").on("blur", function() {
            let text = $(this).text();
            $.post("<?= BASE_URL . "update/tvShow-title" ?>",
                {id: <?= $tvShow["id"] ?>, data: text}
            );
        });

        $("#remove").on("click", function() {
            $("span").remove();
            let validInput = new RegExp("^[0-9]{1,}-(.+?)$");
            if(!validInput.test($("#einput").val())) {
                $('<br/><span class="important">Wrong format!</span>').insertAfter($("#remove"));
                return;
            }

            let s = $("#einput").val().split("-")[0];
            let e = $("#einput").val().split("-")[1].trim();
            
            $.post("<?= BASE_URL . "tvShow/remove-episode" ?>",
                { tvShow_id: <?= $tvShow["id"] ?>, season: s , episode: e },
                function() {
                    let text = $("#einput").val().split("-");
                    let element = $(".season"+text[0]+" p:contains('"+text[1]+"')")[0];
                    let item = $(".season"+text[0]);
                    for (let i=item.children().length-1; i>0; i--) {
                        let value = (($(item.children()[i]).text()).slice(0, -2)).trim();
                        if (text[1].trim().toLowerCase() === value.toLowerCase()) {
                            $(item.children()[i]).remove();
                            break;
                        }
                    }
                    if (item.children().length == 1) {
                        $(item).remove();
                    }
                    $("#einput").val("");

                }
            )

        });

        $("#add").on("click", function() {
            $(".span").remove();
            let validInput = new RegExp("^[0-9]{1,}-(.+?)$");
            if(!validInput.test($("#einput").val())) {
                $('<p class="span"><span class="important">Wrong format!</span></p>').insertAfter($("#remove"));
                return;
            }

            let s = $("#einput").val().split("-")[0];
            let e = $("#einput").val().split("-")[1].trim();

            $.post("<?= BASE_URL . "tvShow/add-episode" ?>",
                { tvShow_id: <?= $tvShow["id"] ?>, season: s , episode: e },
                function(episode) {
                    if (!$(".season"+episode["season"])[0]) {
                        $("#addEpisodes").append('<div class="season'+episode["season"]+'"><p class="p" onclick="show_hide()" style="font-size:150%">Season '+episode["season"]+'</p></div>');
                        $(".p:last").on("click", show_hide);
                    }
                    $(".season"+episode["season"]).append('<p style="color: silver; margin-left: 20px; overflow-wrap: break-word;">'+episode["episode"]+
                                                        ' <button class="radio" name="'+episode["episode"]+'" value="'+episode["watched"]+'">✓</button'+
                                                        '</p>'); 
                    $(".radio:last").on("click", watch);

                    $("#einput").val("");

                }

            )
        });

        $("#descriptionText").on("change", function() {
                let text = $(this).val();     
                $.post("<?= BASE_URL . "update/tvShow-description" ?>", 
                    { id: <?= $tvShow["id"] ?>, data: text }
                );
            });

        <?php if ($tvShow["favorite"]): ?>
                $("#favorite").css("background-color", "red");
            <?php else: ?>
                $("#favorite").css("background-color", "white");
            <?php endif; ?>


            $("#favorite").on("click", function() {
                $.post("<?= BASE_URL . "favorites/tvShow-update" ?>",
                    { id: <?= $tvShow["id"] ?> },
                    function() {
                       if ($("#favorite").css("background-color")=="rgb(255, 0, 0)") {
                           $("#favorite").css("background-color", "white");
                       } else {
                            $("#favorite").css("background-color", "red");
                       }
                    }
                );
            });

        $(".radio").on("click", watch);

        $(".p").on("click", show_hide);

    });

    function watch() {
        let item = $(this);
        $.post("<?= BASE_URL . "tvShow/edit" ?>",
            {id: <?= $tvShow["id"] ?>, episode: $(this).attr("name")},
            function() {
                if (item.css("background-color") == "rgb(0, 128, 0)") {
                    item.css("background-color", "white");
                } else {
                    item.css("background-color", "green");
                }
            }
        )         
    }

    function show_hide() {
        if($(this).parent().children(':visible').length <= 1) {
            $(this).parent().children().show();
        } else {
            $(this).parent().children().not(":first").hide();
        }
    }


</script>