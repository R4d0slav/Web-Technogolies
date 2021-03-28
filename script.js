$(document).ready(() => {
    init();
    boxSlide();
});

// type = tvShows or movies !!

function init() {
    // localStorage.clear();
    initLocals();
    initDescription();
    initGrid();
}

function initGrid() {
    if (window.innerWidth<900) {
        let s;
        if ($("#currentSite").text()=="Tv-Shows") {
            s = '"header search search"'+
                '"navBar tvShows tvShows"'+
                '"addDiv tvShows tvShows"'+
                '"footer footer footer"';
        } else {
            s = '"header search search"'+
                '"navBar movies movies"'+
                '"addDiv movies movies"'+
                '"footer footer footer"';
        }
        $(".otherBody").css("grid-template-areas",  s); 
    }
}

function boxSlide() {
    var boxWidth = $("#add").width();

    $("#add").css("width", boxWidth);
    // $(".otherBody").css("grid-template-columns", "15% 55% 30%");

    $("#add").on("mouseenter", function() { 
        if (window.innerWidth>900) {
            $("#add").animate({ width: boxWidth*2.5 }, 100);
            $(".otherBody").css("grid-template-columns", "33% 37% 30%");
        }
        // $(".otherBody").css("grid-template-columns", "70% 10% 20%");

    }).on("mouseleave", function() { 
        $("#add").animate({ width: boxWidth}, 100);
        if (window.innerWidth>900) {
        $(".otherBody").css("grid-template-columns", "14% 56% 30%");
        }
        // $(".otherBody").css("grid-template-columns", "30% 20% 50%");
    })
}


//INIT
function initLocals() {
    if (!localStorage.getItem("tvShows")) {
        $.get("data.json", function(data){
            localStorage.setItem("tvShows", JSON.stringify(data.tvShows));
            initShows();
        });
        // localStorage.setItem("tvShows", "[]");
    } else {
        initShows();
    }
    if (!localStorage.getItem("movies")) {
        $.get("data.json", function(data){
            localStorage.setItem("movies", JSON.stringify(data.movies));
            initMovies();
        })
        // localStorage.setItem("movies", "[]");
    } else {
        initMovies();
    }
    if (!localStorage.getItem("searched")) {
        localStorage.setItem("searched", "");
    }
    initSearched();

    if (!localStorage.getItem("favorites")) {
        localStorage.setItem("favorites", "[]");
    }
    // if (!localStorage.getItem("favoriteMovies")) {
    //     localStorage.setItem("favoriteMovies", "[]");
    // }
    initFavorites();
    // refreshLocals();
}

function initShows() {
    let shows = JSON.parse(localStorage.tvShows);
    for (show of shows.reverse()) {
        addTvShowOrMovie(show.url, show.name, "tvShows");
    }
}

function initMovies() {
    let movies = JSON.parse(localStorage.movies);
    for (movie of movies.reverse()) {
        addTvShowOrMovie(movie.url, movie.name, "movies");
    }
}

function initFavorites() {
    let favs = JSON.parse(localStorage.favorites);
    for (const fav of favs.reverse()) {
        addTvShowOrMovie(fav.data.url, fav.data.name, "F"+fav.type);
    }
}

//ADD
function addTvShowOrMovie(url, name, type) {
    let img = '<img src='+url+' alt='+name+'>';
    $("#"+type).append("<div class='content_img'>"+img+"<p>"+name+"</p>"+"</div>");
    $("#"+type+" div:last").click(test);
}

function addNewItem(type, url, name) {
    addTvShowOrMovie(url, name, type);
    const object = {
        url: url,
        name: name
    };
    let items = JSON.parse(localStorage.getItem(type));
    items.push(object);
    localStorage.setItem(type, JSON.stringify(items));
}
//SEARCH
function initSearched() {
    let searched = localStorage.searched;
    if (searched != "") {
        searchLocals(searched);
    }
}

function search(name) {
    if (!name.match(/^[a-z0-9]+$/i)) {
        return
    }
    setTimeout(() => { 
        window.location.href = "search.html";
        searched = name.toLowerCase().replace(/[^\w\s!?]/g,'');        
        localStorage.setItem("searched", searched);
    }, 0);
}


function searchLocals(searched) {
    let shows = JSON.parse(localStorage.tvShows);
    let movies = JSON.parse(localStorage.movies);
    let list = new Array();

    for (const [i,show] of shows.entries()) {
        // name = show.name.toLowerCase().replace(/[^\w\s!?]/g,'');
        // name2 = movie.name.toLowerCase().replace(/[^\w\s!?]/g,'');
        if (checkWords(show.name.toLowerCase().replace(/[^\w\s!?]/g,''), searched) || show.name === searched) {
            addSearchedItem("StvShows", show);
            list.push({data: show, id: i, type: "tvShows"});
        }              
    }
    for (const [i,movie] of movies.entries()) {
        if (checkWords(movie.name.toLowerCase().replace(/[^\w\s!?]/g,''), searched) || movie.name === searched) {
            addSearchedItem("Smovies", movie);
            list.push({data: movie, id: i, type: "movies"});
        } 
    }
    // localStorage.description = JSON.stringify(list);
    return list;
}

function checkWords(s1, s2) {
    let w1 = s1.split(" ");
    let w2 = s2.split(" ");
    for (x of w1) {
        for (y of w2) {
            if (x == y) {
                return true;
            }
        }
    }
    return false;
}

function addSearchedItem(type, movie) {
    let img = '<img src='+movie.url+' alt='+movie.name+'>';
    $("#"+type).append("<div class='content_img'>"+img+"<p>"+movie.name+"</p>"+"</div>");
    $("#"+type+" div:last").click(test);
}

//DESCRIPTION
function initDescription() {
    if (!localStorage.getItem("description")) {
        localStorage.setItem("description", "[]");
    }
    if (localStorage.description!="") {
        find();
    }
    // $("#description").append("<h2>"+localStorage.description+"</h2>");
}

async function find() {
    // console.log($(this));
    // const t = await fetch(searchJSON(localStorage.description));
    // await fetch(searchJSON(localStorage.description))
    // .then(data => console.log(data));
    // let name = JSON.parse(localStorage.description);
    // console.log(name);
    let d = localStorage.description;
    // localStorage.description = "";
    let f = searchLocals(d);

    for (const item of f) {
        if (item.data.name === d) {
            describe(item);
        }
    }
}

function describe(item) {
    // localStorage.setItem("tvShows", JSON.stringify("blabla"));
    // console.log(item.data.description);
    let img = '<img src='+item.data.url+' alt='+item.data.name+'>';
    $("#description_title").append("<h1>"+item.data.name+"</h1>");
    $("#description_img").append(img);
    $("#description_area").append("<textarea id='description_input' name='describe' onchange=changedDescription();>"+item.data.description+"</textarea>");
    // $("#description").append("<h1>"+item.season1+"</h1>");
    //favorite button
    $("#description_area").append("<br /><button id='favoriteItem'>Favorite</button>");
    if (searchFavorites(item.data.name)>=0) {
        $("#favoriteItem").css("background", "red");
    }
    $("#favoriteItem").click(favoriteItem);
    $("#favoriteItem").css("margin", "10px");

    //delete button
    $("#description_area").append("<button id='removeItem'>Delete</button>");
    $("#removeItem").click(removeItem);

    if (item.type=="tvShows") {
        addContent(item);
    }
}

function changedDescription() {
    const input = $("#description_input").val();
    let item = $(searchLocals(localStorage.description));
    let items = JSON.parse(localStorage.getItem(item[0].type));
    // console.log(items[item[0].id].description+"\n", "\n"+input);

    items[item[0].id].description = input;
    localStorage.setItem(item[0].type, JSON.stringify(items));
    // initDescription();
}

function favoriteItem() {
    let item = searchLocals($(this).parent(":first").children(":first").text());
    let favs = JSON.parse(localStorage.favorites);
    let alreadyFavorited = searchFavorites(item[0].data.name);
    // let s = "";
    if (alreadyFavorited>=0) {
        favs.splice(alreadyFavorited, 1);
        // s = "Removed from Favorites";
        $("#favoriteItem").css("background", "white");
    } else {
        favs.push(item[0]);
        // s = "Added to Favorites";
        $("#favoriteItem").css("background", "red");
    // favs.push(item[0]);
    }
        localStorage.favorites = JSON.stringify(favs);
        // alert(item[0].data.name);
        // $("#favoriteItem").css("background", "red");
        // $("#description_area").append("<p>"+s+"</p>");
        // setTimeout(()=>{
        //     $("#description_area").children().last().remove();
        // }, 1000);
    
}

function searchFavorites(name) {
    let items = JSON.parse(localStorage.favorites);
    for (const [i,item] of items.entries()) {
        if (item.data.name === name) {
            return i;
        }
    }
    return -1;
}

function removeItem() {
    let item = searchLocals($(this).parent(":first").children(":first").text());
    let result = confirm("Are you sure you want to delete "+item[0].data.name);
    if (!result) {
        return;
    }
    let tvShows = JSON.parse(localStorage.getItem(item[0].type));
    tvShows.splice(item[0].id, 1);
    localStorage.setItem(item[0].type, JSON.stringify(tvShows));
   
    let favPos = searchFavorites(item[0].data.name);
    if (favPos>=0) {
        let favs = JSON.parse(localStorage.favorites);
        favs.splice(favPos, 1);
        localStorage.favorites = JSON.stringify(favs);
    }
    
    setTimeout(() => { 
        window.location.href = "index.html";
    }, 0);
}

function addContent(item) {
    if (!item.data.seasons) {
        item.data.seasons = {};
    }
    $("#description_add").append("<form>"+
                                "<input type='text' required placeholder='E.g. 1-Name' title='Enter season number and name of episode'></input>"+
                                "<button type='submit' onclick='addEpisode();'>Add</button>"+
                                "<button type='submit' onclick='removeEpisode();'>Remove</button>"+
                                "</form>");
    let seasons = Object.keys(item.data.seasons);
    seasons.pop();
    for (let season of seasons) {
        $("#description_content").append("<ul><li>Season "+season+"</li>");
        $("#description_content ul>li:last").click(function() {
            if ($(this).children().is(":visible")) {
                $(this).children().hide();
            } else {
                $(this).children().show();
            }
        });
        $("#description_content li:last").append("<ol>");
        for (let episode of item.data.seasons[season]) {
            $("#description_content ol:last").append("<li>"+episode+"</li>");
        }
        $("#description_content ol:last").hide();
        $("#description_content ol:last").append("</ol></ul>");
    }
}

function addEpisode() {
    let input = $("#description_add input").val().split("-");
    let pos = searchLocals(localStorage.description)[0].id;
    let items = JSON.parse(localStorage.tvShows);
    if ((input.length>1 && isNaN(parseInt(input[0]))) || input.length==1) {
        return;
    }
    if (!items[pos].seasons) {
        items[pos].seasons = {"seasons":"0"};
    }
    let seasons = Object.keys(items[pos].seasons);
    seasons.pop();
    if (!seasons.includes(input[0])) {
        items[pos].seasons["seasons"]++;
        items[pos].seasons[input[0]]=[input[1]];
    } else {
        items[pos].seasons[input[0]].push(String(input[1]));
    }
    localStorage.tvShows = JSON.stringify(items);
}

function removeEpisode() {
    let input = $("#description_add input").val().split("-");
    if ((input.length>1 && isNaN(parseInt(input[0]))) || input.length==1) {
        return;
    }
    let pos = searchLocals(localStorage.description)[0].id;
    let items = JSON.parse(localStorage.tvShows);

    if (!items[pos].seasons[input[0]]) {
        return;
    }

    for (const [i,value] of (items[pos].seasons[input[0]]).entries()) {
        if (value === input[1]) {
            items[pos].seasons[input[0]].splice(i, 1);
            break;
        }
    }

    if (items[pos].seasons[input[0]].length == 0  || input[1]=="*") {
        delete items[pos].seasons[input[0]];
        items[pos].seasons["seasons"]--;
    }

    localStorage.tvShows = JSON.stringify(items);
}



function test() {

    setTimeout(() => { 
        window.location.href = "description.html";
        // searched = name.toLowerCase().replace(/[^\w\s!?]/g,'');        
        // localStorage.setItem("searched", searched);
        // $("#description div:last").append("<div><p>Hi</p></div>");
        // $("#description").append("<div class='content_img'>"+img+"<p>"+name+"</p>"+"</div>");
        // console.log("da");
        localStorage.description = $(this).text();
    }, 0);

    // $("#description div:last")
}


// if (!localStorage.getItem("tvShows")) {
//         localStorage.setItem("tvShows", "[]");
//         $.getJSON("data.json", function(data){
//             localStorage.tvShows = JSON.stringify(data.tvShows);
//             initTvShows();
//         });
//     }
