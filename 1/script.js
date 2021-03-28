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
    if (window.innerWidth<600) {
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
    if (window.innerWidth>1200) {
    var boxWidth = $("#add").width();

    $("#add").on("mouseenter", function() { 
        $("#add").animate({ width: boxWidth*2.5 }, 100);
        $(".otherBody").css("grid-template-columns", "33% 37% 30%");
    }).on("mouseleave", function() { 
        $("#add").animate({ width: boxWidth}, 100);
        $(".otherBody").css("grid-template-columns", "14% 56% 30%");
    });
    }
}

// function fileExists(url) {
//     alert("da");
//     var http = new XMLHttpRequest();
//     http.open('HEAD', url, false);
//     http.send();
//     return http.status!=404;
// }

//INIT
function initLocals() {
    if (!localStorage.getItem("tvShows")) {
        initLocalShows();
    }
    initShows();
    
    if (!localStorage.getItem("movies")) {
            initLocalMovies();
        }
    initMovies();
    if (!localStorage.getItem("searched")) {
        localStorage.setItem("searched", "");
    }
    initSearched();

    if (!localStorage.getItem("favorites")) {
        localStorage.setItem("favorites", "[]");
    }
    initFavorites();
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
    $("#"+type+" div:last").click(goToDescription);
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
    console.log(localStorage.movies);
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
        localStorage.description = "";
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
    $("#"+type+" div:last").click(goToDescription);
}

//DESCRIPTION
function goToDescription() {
    setTimeout(() => { 
        window.location.href = "description.html";
        localStorage.description = $(this).text();
    }, 0);
}

function initDescription() {
    if (!localStorage.getItem("description")) {
        localStorage.setItem("description", "[]");
    }
    if (localStorage.description!="") {
        find();
    }
}

async function find() {
    let description = localStorage.description;
    let found = searchLocals(description);

    for (const item of found) {
        if (item.data.name === description) {
            describe(item);
        }
    }
}

function describe(item) {
    let img = '<img src='+item.data.url+' alt='+item.data.name+'>';
    $("#description_title").append("<h1>"+item.data.name+"</h1>");
    $("#description_img").append(img);
    $("#description_area").append("<textarea id='description_input' name='describe' onchange=changedDescription();>"+item.data.description+"</textarea>");
    $("#description_area").append("<br /><button id='favoriteItem'>Favorite</button>");
    if (searchFavorites(item.data.name)>=0) {
        $("#favoriteItem").css("background", "red").css("color", "white");
    }
    $("#favoriteItem").click(favoriteItem);
    $("#favoriteItem").css("margin", "10px");

    //delete button
    $("#description_area").append("<button id='removeItem'>Delete</button>");
    $("#removeItem").click(removeItem);

    //Check if it's tvShows to add Seasons
    if (item.type=="tvShows") {
        addContent(item);
    }
}

function changedDescription() {
    const input = $("#description_input").val();
    let item = $(searchLocals(localStorage.description));
    let items = JSON.parse(localStorage.getItem(item[0].type));

    items[item[0].id].description = input;
    localStorage.setItem(item[0].type, JSON.stringify(items));
}

function favoriteItem() {
    let item = searchLocals($(this).parent(":first").children(":first").text());
    let favs = JSON.parse(localStorage.favorites);
    let alreadyFavorited = searchFavorites(item[0].data.name);
    if (alreadyFavorited>=0) {
        favs.splice(alreadyFavorited, 1);
        //Removed from Favorites
        $("#favoriteItem").css("background", "white").css("color", "black");
    } else {
        favs.push(item[0]);
        //Added to Favorites
        $("#favoriteItem").css("background", "red").css("color", "white");
    }
        localStorage.favorites = JSON.stringify(favs);
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
        window.location.href = "home.html";
    }, 0);
}

function addContent(item) {
    if (!item.data.seasons) {
        item.data.seasons = {};
    }
    $("#description_add").append("<p>Add/Remove an Episode</p>"+
                                "<form>"+
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


// SOME DEFAULT DATA

function initLocalShows() {
    let shows = [{       "url": "https://m.media-amazon.com/images/M/MV5BMDNkOTE4NDQtMTNmYi00MWE0LWE4ZTktYTc0NzhhNWIzNzJiXkEyXkFqcGdeQXVyMzQ2MDI5NjU@._V1_.jpg",
    "name": "The Office",
    "description": "A motley group of office workers go through hilarious misadventures at the Scranton, Pennsylvania, branch of the Dunder Mifflin Paper Company.",
    "favorite": "true",
    "seasons":
        {
            "seasons":"9",
            "1":["Pilot", "Diversity Day", "Health Care", "The Alliance", "Basketball", "Hot Girl"],
            "2":["The Dundies", "Sexual Harassment", "Office Olympics", "The Fire", "Halloween", "The Fight", "The Client", "Performance Review", "Email Surveillance", "Christmas Party", "Booze Cruise", "The Injury", "The Secret", "The Carpet", "Boys and Girls", "Valentine's Day", "Dwight's Speech", "Take Your Daughter to Work Day", "Michael's Birthday", "Drug Testing", "Conflict Resolution", "Casino Night"],
            "3":["Gay Witch Hunt", "The Convention", "The Coup", "Grief Counseling", "Initiation", "Diwali", "Branch Closing", "The Merger", "The Convict", "A Benihana Christmas", "Back from Vacation", "Traveling Salesmen", "The Return", "Ben Franklin", "Phyllis' Wedding", "Business School", "Cocktails", "The Negotiation", "Safety Training", "Product Recall", "Women's Appreciation", "Beach Games", "The Job"],
            "4":["Fun Run", "Dunder Mifflin Infinity", "Launch Party", "Money", "Local Ad", "Branch Wars", "Survivor Man", "The Deposition", "Dinner Party", "Chair Model", "Night Out", "Did I Stutter?", "Job Fair", "Goodbye, Toby"],
            "5":["Weight Loss", "Business Ethics", "Baby Shower", "Crime Aid", "Employee Transfer", "Customer Survey", "Business Trip", "Frame Toby", "The Surplus", "Moroccan Christmas", "The Duel", "Prince Family Paper", "Stress Relief", "Lecture Circuit: Part 1", "Lecture Circuit: Part 2", "Blood Drive", "Golden Ticket", "New Boss", "Two Weeks", "Dream Team", "Michael Scott Paper Company", 	"Heavy Competition", "Broke", "Casual Friday", "Cafe Disco", "Company Picnic"],
            "6":["Gossip", "The Meeting", "The Promotion", "Niagara", "Mafia", "The Lover", "Koi Pond", "Double Date", "Murder", "Shareholder Meeting", "Scott's Tots", "Secret Santa", "The Banker", "Sabre", "The Manager and the Salesman", "The Delivery", "St. Patrick's Day", "New Leads", "Happy Hour", "Secretary's Day", "Body Language", "The Cover-Up", "The Chump", "Whistleblower"],
            "7":["Nepotism", "Counseling", "Andy's Play", "Sex Ed", "The Sting", "Costume Contest", "Christening", 	"Viewing Party", "WUPHF.com", "China", "Classy Christmas", "Ultimatum", "The Seminar", "The Search", "PDA", "Threat Level Midnight", "Todd Packer", "Garage Sale", 	"Training Day", "Michael's Last Dundies", "Goodbye, Michael", "The Inner Circle", "Dwight K. Schrute, (Acting) Manager", "Search Committee"],
            "8":["The List", "The Incentive", "Lotto", "Garden Party", "Spooked", "Doomsday", "Pam's Replacement", "Gettysburg", "Mrs. California", "Christmas Wishes", "Trivia", "Pool Party", "Jury Duty", "Special Project", "Tallahassee", "After Hours", "Test the Store", "Last Day in Florida", "Get the Girl", "Welcome Party", "Angry Andy", "Fundraiser", "Turf War", "Free Family Portrait Studio"],
            "9":["New Guys", "Roy's Wedding"	, "Andy's Ancestry", "Work Bus", "Here Comes Treble", "The Boat", "The Whale", "The Target", "Dwight Christmas", "Lice", "Suit Warehouse", "Customer Loyalty", "Junior Salesman", "Vandalism", "Couples Discount", "Moving On", "The Farm", "Promos", "Stairmageddon", "Paper Airplane", "Livin' the Dream", "A.A.R.M."	, "Finale"]
        }
},
{       "url": "https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSHW7M_Pw-BVdUw5Mkw6imk1dteP1z0bQY73g9DJkfU-M-WM4Nq",
    "name": "F.R.I.E.N.D.S",
    "description": "Follow the lives of six reckless adults living in Manhattan, as they indulge in adventures which make their lives both troublesome and happening.",
    "seasons": 
        {
            "seasons":"5",
            "1":["The Pilot", "The One with the Sonogram at the End", "The One with the Thumb", "The One with George Stephanopoulos", "The One with the East German Laundry Detergent", "The One with the Butt", "The One with the Blackout", "The One Where Nana Dies Twice", "The One Where Underdog Gets Away", "The One with the Monkey", "The One with Mrs. Bing", "The One with the Dozen Lasagnas", "The One with the Boobies", "The One with the Candy Hearts", "The One with the Stoned Guy", "The One with Two Parts: Part 1", "The One with Two Parts: Part 2", "The One with All the Poker", 	"The One Where the Monkey Gets Away", "The One with the Evil Orthodontist", "The One with the Fake Monica", "The One with the Ick Factor", "The One with the Birth", "The One Where Rachel Finds Out"],
            "2":["The One with Ross's New Girlfriend", "The One with the Breast Milk", "The One Where Heckles Dies", "The One with Phoebe's Husband", "The One with Five Steaks and an Eggplant", "The One with the Baby on the Bus", "The One Where Ross Finds Out", "The One with the List", "The One with Phoebe's Dad", "The One with Russ", "The One with the Lesbian Wedding", "The One After the Superbowl", "The One with the Prom Video", "The One Where Ross and Rachel...You Know", "The One Where Joey Moves Out", "The One Where Eddie Moves In", "The One Where Dr. Ramoray Dies", "The One Where Eddie Won't Go", "The One Where Old Yeller Dies", "The One with the Bullies", "The One with the Two Parties", "The One with the Chicken Pox", "The One with Barry and Mindy's Wedding"],
            "3":["The One with the Princess Leia Fantasy", "The One Where No One's Ready", "The One with the Jam", "The One with the Metaphorical Tunnel", "The One with Frank Jr.", "The One with the Flashback", "The One with the Race Car Bed", "The One with the Giant Poking Device", "The One with the Football", "The One Where Rachel Quits", "The One Where Chandler Can't Remember Which Sister", "The One with All the Jealousy", "The One Where Monica and Richard Are Just Friends", "The One with Phoebe's Ex-Partner", "The One Where Ross and Rachel Take a Break", "The One with the Morning After", "The One Without the Ski Trip", "The One with the Hypnosis Tape", "The One with the Tiny T-Shirt", "The One with the Dollhouse", "The One with a Chick and a Duck", "The One with the Screamer", "The One with Ross's Thing", "The One with the Ultimate Fighting Champion", "The One at the Beach"],
            "4":["The One with the Jellyfish", "The One with the Cat", "The One with the Cuffs", "The One with the Ballroom Dancing", "The One with Joey's New Girlfriend", "The One with the Dirty Girl", "The One Where Chandler Crosses the Line", "The One with Chandler in a Box", "The One Where They're Going to Party!", "The One with the Girl from Poughkeepsie", "The One with Phoebe's Uterus", "The One with the Embryos", "The One with Rachel's Crush", "The One with Joey's Dirty Day", "The One with All the Rugby", "The One with the Fake Party", "The One with the Free Porn", "The One with Rachel's New Dress", "The One with All the Haste", "The One with All the Wedding Dresses", "The One with the Invitation", "The One with the Worst Best Man Ever", "The One with Ross' Wedding"],
            "5":["The One After Ross Says Rachel", "The One with All the Kissing", "The One Hundredth", "The One with the Triplets", "The One Where Phoebe Hates PBS", "The One with the Kips", "The One with the Yeti", "The One Where Ross Moves In", "The One with All the Thanksgivings", "The One with Ross's Sandwich", "The One with the Inappropriate Sister", 	"The One with All the Resolutions", "The One with Chandler's Work Laugh", "The One with Joey's Bag", "The One Where Everybody Finds Out", "The One with the Girl Who Hits Joey", "The One with the Cop", "The One with Rachel's Inadvertent Kiss", "The One Where Rachel Smokes", "The One Where Ross Can't Flirt", "The One with the Ride-Along", "The One with the Ball", "The One with Joey's Big Break", "The One in Vegas"]

        }
},  
{       "url": "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRfSaVJADtNW0f28A0YJ1sGeS4oSMfpkZryxeNNZaUEVKgkeVpV",
    "name": "How I Met Your Mother",
    "description": "Ted Mosby, an architect, recounts to his children the events that led him to meet their mother. His journey is made more eventful by the presence of his friends Lily, Marshall, Robin and Barney.",
    "seasons": 
        {
            "seasons":"0"
        }
},
{       "url": "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQvtC9eemY3y3TKXvkupIecZjCTteQW65ntB3zhUi1StR7brfDd",
    "name": "The Big Bang Theory",
    "description": "The lives of four socially awkward friends, Leonard, Sheldon, Howard and Raj, take a wild turn when they meet the beautiful and free-spirited Penny.",
    "seasons": 
        {
            "seasons":"0"
        }
},
{       "url": "https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcT4AE1CiZNPWOr58pjD9ShIMNVxriF8MqBQJ7mrB4PrSKD8vp5J",
    "name": "Malcolm In The Middle",
    "description": "Malcolm, a bright and intelligent boy, lives with his dysfunctional family while dealing with the troubles of being the middle child and a teenager."
},
{       "url": "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcSi6gpR8IDM1RF5TFgtoLybSA9Ywg_XLJj26ifA0q9YIGBfuQrI",
    "name": "Vikings",
    "description": "Ragnar Lothbrok, a legendary Norse hero, is a mere farmer who rises up to become a fearless warrior and commander of the Viking tribes with the support of his equally ferocious family.",
    "seasons":
        {
            "seasons":"6",
            "1":["Rites of Passage", "Wrath of the Northmen", "Dispossessed", "Trial", 	"Raid", "Burial of the Dead", "A King's Ransom", "Sacrifice", "All Change"],
            "2":["Brother's War", "Invasion", "Treachery", "Eye for an Eye", "Answers in Blood", "Unforgiven", "Blood Eagle", "Boneless", "The Choice", "The Lord's Prayer"],
            "3":["Mercenary", "The Wanderer", "Warrior's Fate", "Scarred", "The Usurper", "Born Again", "Paris", "To the Gates!", "Breaking Point", "The Dead"],
            "4":["A Good Treason", "Kill the Queen", "Mercy", "Yol", "Promised", "What Might Have Been", "The Profit and the Loss", "Portage", "Death All 'Round", "The Last Ship", "The Outsider", "The Vision", "Two Journeys", "In the Uncertain Hour Before the Morning", "All His Angels", "Crossings", "The Great Army", "Revenge", "On the Eve", "The Reckoning"],
            "5":["The Fisher King", "The Departed", "Homeland", "The Plan", "The Prisoner", "The Message", 	"Full Moon", "The Joke", "A Simple Story", "Moments of Vision", "The Revelation", "Murder Most Foul", "A New God", "The Lost Moment", "Hell", "The Buddha", "The Most Terrible Thing", "Baldur", "What Happens in the Cave", "Ragnarok"],
            "6":["New Beginnings", "The Prophet", "Ghosts, Gods and Running Dogs", "All the Prisoners", "The Key", 	"Death and the Serpent", "The Ice Maiden", "Valhalla Can Wait", "Resurrection", "The Best Laid Plans", "King of Kings", "All Change", "The Signal", "Lost Souls", "All at Sea", "The Final Straw", "The Raft of Medusa", "It's Only Magic", "The Lord Giveth…", "The Last Act"]
        }
},
{       "url": "https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcRMsyv-7KLXpaqqRnnuPrW6kDi-2SAQzsoYDkFybg4H-f7HVFZ1",
    "name": "Mr. Robot",
    "description": "Elliot, a cyber-security engineer suffering from anxiety, works for a corporation and hacks felons by night. Panic strikes him after Mr Robot, a cryptic anarchist, recruits him to ruin his company.",
    "seasons":
        {
            "seasons": "4",
            "1":["eps1.0_hellofriend.mov", "eps1.1_ones-and-zer0es.mpeg", "eps1.2_d3bug.mkv", "eps1.3_da3m0ns.mp4", "eps1.4_3xpl0its.wmv", "eps1.5_br4ve-trave1er.asf", "eps1.6_v1ew-s0urce.flv", "eps1.7_wh1ter0se.m4v", "eps1.8_m1rr0r1ng.qt", "eps1.9_zer0-day.avi"],
            "2":["eps2.0_unm4sk-pt1.tc", "eps2.0_unm4sk-pt2.tc", "eps2.1_k3rnel-pan1c.ksd", "eps2.2_init_1.asec", "eps2.3_logic-b0mb.hc", "eps2.4_m4ster-s1ave.aes", "eps2.5_h4ndshake.sme", "eps2.6_succ3ss0r.p12", "eps2.7_init_5.fve", "eps2.8_h1dden-pr0cess.axx", "eps2.8_h1dden-pr0cess.axx", "eps2.9_pyth0n-pt2.p7z"],
            "3":["eps3.0_power-saver-mode.h", "eps3.1_undo.gz", "eps3.2_legacy.so", "eps3.3_metadata.par2", "eps3.4_runtime-error.r00", "eps3.5_kill-process.inc", "eps3.6_fredrick+tanya.chk", "eps3.7_dont-delete-me.ko", "eps3.8_stage3.torrent", "shutdown -r"],
            "4":["401 Unauthorized", "402 Payment Required", "403 Forbidden", "404 Not Found", "405 Method Not Allowed", "406 Not Acceptable", "407 Proxy Authentication Required", "408 Request Timeout", "409 Conflict", "410 Gone", "eXit", "whoami", "Hello, Elliot"]
        }
},
{       "url": "https://moviebird.com/wp-content/uploads/2018/06/Dr-Who.jpg",
    "name": "Doctor Who",
    "description": "The adventures of the doctor who is an time traveling alien from the planet Galifrey. He has commons and space and lots more."
},
];

    localStorage.setItem("tvShows", JSON.stringify(shows));
}

function initLocalMovies() {
    let movies = [{   "url":"https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcT9J7XACn3tlD6v4UXRMvT2wJN8FGCCPeh8U3RkZ6__tR4wGhSo",
    "name": "The Lord Of The Rings - The Fellowship Of The Ring",
    "description": "A young hobbit, Frodo, who has found the One Ring that belongs to the Dark Lord Sauron, begins his journey with eight companions to Mount Doom, the only place where it can be destroyed."
},
{   "url":"https://cdn.hmv.com/r/w-1280/hmv/files/35/358d9ebd-3be4-4ef3-a412-9c0cbfc626e3.jpg",
    "name": "The Lord Of The Rings - The Two Towers",
    "description": "Frodo and Sam arrive in Mordor with the help of Gollum. A number of new allies join their former companions to defend Isengard as Saruman launches an assault from his domain."
},
{   "url":"https://lightbox-prod.imgix.net/images/assets/100140981-p33156_v_v10_ab.jpg",
    "name": "The Lord Of The Rings - The Return Of The King",
    "description": "The former Fellowship members prepare for the final battle. While Frodo and Sam approach Mount Doom to destroy the One Ring, they follow Gollum, unaware of the path he is leading them to."
},
{   "url":"https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTS1VqOgP7iJC44UcztFaTbvD0OzoRymEhXfMPlgq7FPY0OEvCj",
    "name": "The Hobbit - An Unexpected Journey",
    "description": "Bilbo Baggins, a hobbit, is persuaded into accompanying a wizard and a group of dwarves on a journey to reclaim the city of Erebor and all its riches from the dragon Smaug."
},
{   "url":"https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcT8e9aFTxDo5jCIaaDNYgNcjJ4JFIz8MVlJr3-nhggVytaoFLOu",
    "name": "The Hobbit - The Desolation Of Smaug",
    "description": "Bilbo Baggins, a hobbit, and his companions face great dangers on their journey to Laketown. Soon, they reach the Lonely Mountain, where Bilbo comes face-to-face with the fearsome dragon Smaug."
},
{   "url":"https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRYIyIkKo5-RKlfC1xnBvJMrHH-DbSUmS9NwN9MaJkjjE_f4DP8",
    "name": "The Hobbit - The Battle Of The Five Armies",
    "description": "Bilbo fights against a number of enemies to save the life of his Dwarf friends and protects the Lonely Mountain after a conflict arises."
},
{   "url":"https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRKTcTqELNNPpy-c6orc876-Yxo-_QKENBdIufLEZNlSjHQBj_i",
    "name": "Pirates Of The Carribean - The Curse Of The Black Pearl",
    "description": "A blacksmith joins forces with Captain Jack Sparrow, a pirate, in a bid to free the love of his life from Jack's associates, who kidnapped her suspecting she has the medallion."
},
{   "url":"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTKp5KKVpoYkHVFoFCD0xC-FWBN1ZA2TTe20MNswXlzJ8I06QCM",
    "name": "Pirates Of The Carribean - Dead Man's Chest"
},
{   "url":"https://m.media-amazon.com/images/M/MV5BMjIyNjkxNzEyMl5BMl5BanBnXkFtZTYwMjc3MDE3._V1_UY1200_CR90,0,630,1200_AL_.jpg",
    "name": "Pirates Of The Carribean - At World's End"
},
{   "url":"https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSkmMH-bEDUS2TmK8amBqgIMgrfzN1_mImChPuMrunA1XjNTSKm",
    "name": "The Shawshank Redemption",
    "description": "Andy Dufresne, a successful banker, is arrested for the murders of his wife and her lover, and is sentenced to life imprisonment at the Shawshank prison. He becomes the most unconventional prisoner."
},
{   "url":"https://upload.wikimedia.org/wikipedia/en/5/52/Good_Will_Hunting.png",
    "name": "Good Will Hunting"
}];
    localStorage.setItem("movies", JSON.stringify(movies));
}