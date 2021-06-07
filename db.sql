create database library;
use library;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `user` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `firstname` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
    `lastname` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
    `username` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
    `email` varchar(45) COLLATE utf8_slovenian_ci NOT NULL,
    `password` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

INSERT INTO `user` VALUES
(1, 'Radoslav', 'Atanasoski', 'radoslav', 'radoslav.atanasoski@gmail.com', '$2y$10$6QZqRs9p/3mOUEVSnu60MuVBeYa24mt945zAIZaWj0amcNwsw7uxC'),
(2, 'first', 'last', 'user', 'user@gmail.com', '$2y$10$r0PrTFgj8yFPYy1VwvIoj.309SYaXqDaF/EJARooyhat/1gPw0zo2');


CREATE TABLE IF NOT EXISTS `movies` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
    `description` varchar(1024) COLLATE utf8_slovenian_ci,
    `year` int(11),
    `img_url` varchar(1024) NOT NULL,
    `user_id` int(11),
    `favorite` boolean DEFAULT false,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;


CREATE TABLE IF NOT EXISTS `tvShows` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
    `description` varchar(1024) COLLATE utf8_slovenian_ci,
    `year` int(11),
    `img_url` varchar(1024) NOT NULL,
    `user_id` int(11),
    `favorite` boolean DEFAULT false,
    PRIMARY KEY (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;

CREATE TABLE IF NOT EXISTS `content` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `season` int(11) NOT NULL,
    `episode` varchar(255) COLLATE utf8_slovenian_ci NOT NULL,
    `watched` boolean DEFAULT false,
    `tvShow_id` int(11) NOT NULL,
    `user_id` int(11),
    PRIMARY KEY (`id`),
    FOREIGN KEY (`tvShow_id`) REFERENCES `tvShows` (`id`),
    FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_slovenian_ci;



INSERT INTO `movies` (`id`, `title`, `description`, `year`, `img_url`) VALUES 
(1, "The Lord of the Rings: The Fellowship of the Ring", "Best movie ever!!", 2001, "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcT9J7XACn3tlD6v4UXRMvT2wJN8FGCCPeh8U3RkZ6__tR4wGhSo"),
(2, "The Lord of the Rings: The Two Towers", "Second movie.", 2002,"https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRQ8FDyks__YDKFPRm3Oj4Dd-yMl7pcGtgIM5IX-nd_6oJQrPiN"),
(3, "The Lord of the Rings: The Return of the King", "Third movie.", 2003, "https://encrypted-tbn2.gstatic.com/images?q=tbn:ANd9GcSyhNpJyWkY1BIsQvBwKIdzq6mzWOqQtiyshuNC0Lh5FeLbcZAw"),
(4, "The Hobbit: An Unexpected Journey", "First movie.", 2012, "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcTS1VqOgP7iJC44UcztFaTbvD0OzoRymEhXfMPlgq7FPY0OEvCj"),
(5, "The Hobbit: The Desolation of Smaug", "Second movie.", 2013, "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcT8e9aFTxDo5jCIaaDNYgNcjJ4JFIz8MVlJr3-nhggVytaoFLOu"),
(6, "The Hobbit: The Battle of the Five Armies", "Third movie.", 2014, "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcRcGhY7S_35Jip7XLsaxJKGyY3VLcez1xxilJzW5LGinYVNqMiC");

INSERT INTO `movies` (`id`, `title`, `description`, `img_url`) VALUES 
(7, "Pirates of the Caribbean: The Curse of the Black Pearl", "First movie.", "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcRKTcTqELNNPpy-c6orc876-Yxo-_QKENBdIufLEZNlSjHQBj_i"),
(8, "Pirates of the Caribbean: Dead Man's Chest", "Second movie.", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcdBS66866ZNWs7MTAnB4AI32LkkD9x3vYV14tfD-WmjF-8hZ2PDJKKZDeSWaf4VawVzs&usqp=CAU"),
(9, "Pirates of the Caribbean: At World's End", "Third movie.", "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcQlOw-O1tdmzrkCgS5iezwNZ3-4iaRQKdCpVU1tQW-9yzGqJOk8"),
(10, "Pirates of the Caribbean: On Stranger Tides", "Forth movie.", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ4hukbs571MwrsRvhFHpXwOf8xeGaehz_9K0VgeosBZeqg4rHg"),
(11, "Pirates of the Caribbean: Salazar's Revenge", "Fifth movie.", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRXhEeDOpouHNg3A75Ngkgl-pQdWrr8ErxSuYCbb8-Tn7KcuD79");

INSERT INTO `tvShows` (`id`, `title`, `img_url`) VALUES
(1, "The Office", "https://encrypted-tbn3.gstatic.com/images?q=tbn:ANd9GcSpUkHaqfWzj4D5wcEQjIZgC-P3A1VGUK4FGR0xT3KPdvGYAsiz"),
(2, "F.R.I.E.N.D.S.", "https://image3.mouthshut.com/images/imagesp/925007481s.jpg"),
(3, "How I Met Your Mother", "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS0DPn96_u8S7YgrrsW7BJnIX-9z_PPALU2_0EtBDJ5c3y2u4oB"),
(4, "Vikings", "https://images-na.ssl-images-amazon.com/images/I/91yFUzjtOuL._SY445_.jpg"),
(5, "Malcolm in the Middle", "https://upload.wikimedia.org/wikipedia/en/3/31/Malcolm_in_the_Middle_%28season_5%29.jpg"),
(6, "Doctor Who", "https://encrypted-tbn1.gstatic.com/images?q=tbn:ANd9GcSfjVBqFE4YvnP-PpJfR49OsQDuGLlQAKV2bnFPOmqbIJr5EQHK"),
(7, "The Big Bang Theory", "https://m.media-amazon.com/images/I/61K8bJwneQL._AC_SY741_.jpg");


INSERT INTO `content` (`id`, `season`, `episode`, `tvShow_id`) VALUES
(1, 1, 'Pilot', 1), (2, 1, 'Diversity Day', 1), (3, 1, 'Health Care', 1), (4, 1, 'The Alliance', 1), (5, 1, 'Basketball', 1), (6, 1, 'Hot Girl', 1),
(7, 2, "The Dundies", 1), (8, 2, "Sexual Harassment", 1), (9, 2, "Office Olympics", 1), (10, 2, "The Fire", 1), (11, 2, "Halloween", 1), (12, 2, "The Fight", 1), (13, 2, "The Client", 1), (14, 2, "Performance Review", 1), (15, 2, "Email Surveilance", 1), (16, 2, "Christmas Party", 1), (17, 2, "Booze Cruise", 1),
(18, 3, "Gay Witch Hunt", 1), (19, 3, "The Convention", 1), (20, 3, "The Coup", 1), (21, 3, "Grief Conseling", 1), (22, 3, "Initiation", 1), (23, 3, "Diwali", 1),(24, 3, "Branch Closing", 1),(25, 3, "The Merger", 1),(26, 3, "The Convict", 1),(27, 3, "A Benihana Christmas", 1),(28, 3, "Back from Vacation", 1),(29, 3, "Traveling Salesmen", 1),(30, 3, "The Return", 1),(31, 3, "Ben Franklin", 1),(32, 3, "Phyllis' Wedding", 1),(33, 3, "Business School", 1),(34, 3, "Cocktails", 1),(35, 3, "The Negotiation", 1),(36, 3, "Safety Training", 1),(37, 3, "Product Recall", 1),(38, 3, "Women's Appreciation", 1),(39, 3, "Beach Games", 1),(40, 3, "The Job", 1);

INSERT INTO `content` (`season`, `episode`, `tvShow_id`) VALUES 
(1, 'Rites of Passage', 4), (1, 'Wrath of the Northmen', 4), (1, 'Dispossessed', 4), (1, 'Trial', 4), (1, 'Raid', 4), (1, 'Burial of the Dead', 4), (1, "A King's Ransom", 4), (1, 'Sacrifice', 4), (1, 'All Change', 4);

INSERT INTO `content` (`season`, `episode`, `tvShow_id`) VALUES 
(1, 'Rose', 6), (1, 'The End of the World', 6), (1, 'The Uniquiet Dead', 6), (1, 'Aliens of London', 6), (1, 'World War Three', 6), (1, 'Dalek', 6), (1, 'The Long Game', 6), (1, "Father's Day", 6), (1, 'The Empty Child', 6), (1, 'The Doctor Dances', 6), (1, 'Boom Town', 6), (1, 'Bad Wolf', 6), (1, 'The Parting of the Ways', 6);


INSERT INTO `movies` (`title`, `description`, `year`, `img_url`, `user_id`, `favorite`) 
SELECT `title`, `description`, `year`, `img_url`, 1 AS `user_id`, `favorite` FROM `movies` WHERE `user_id` IS NULL;

INSERT INTO `movies` (`title`, `description`, `year`, `img_url`, `user_id`, `favorite`) 
SELECT `title`, `description`, `year`, `img_url`, 2 AS `user_id`, `favorite` FROM `movies` WHERE `user_id` IS NULL;



INSERT INTO `tvShows` (`title`, `description`, `year`, `img_url`, `user_id`, `favorite`)
SELECT `title`, `description`, `year`, `img_url`, 1 AS `user_id`, `favorite` FROM `tvShows` WHERE `user_id` IS NULL;

INSERT INTO `tvShows` (`title`, `description`, `year`, `img_url`, `user_id`, `favorite`)
SELECT `title`, `description`, `year`, `img_url`, 2 AS `user_id`, `favorite` FROM `tvShows` WHERE `user_id` IS NULL;



INSERT INTO `content` (`season`, `episode`, `tvShow_id`, `user_id`) VALUES
(1, 'Pilot', 8, 1), (1, 'Diversity Day', 8, 1), (1, 'Health Care', 8, 1), (1, 'The Alliance', 8, 1), (1, 'Basketball', 8, 1), (1, 'Hot Girl', 8, 1),
(2, "The Dundies", 8, 1), (2, "Sexual Harassment", 8, 1), (2, "Office Olympics", 8, 1), (2, "The Fire", 8, 1), (2, "Halloween", 8, 1), (2, "The Fight", 8, 1), (2, "The Client", 8, 1), (2, "Performance Review", 8, 1), (2, "Email Surveilance", 8, 1), (2, "Christmas Party", 8, 1), (2, "Booze Cruise", 8, 1),
(3, "Gay Witch Hunt", 8, 1), (3, "The Convention", 8, 1), (3, "The Coup", 8, 1), (3, "Grief Conseling", 8, 1), (3, "Initiation", 8, 1), (3, "Diwali", 8, 1),(3, "Branch Closing", 8, 1),(3, "The Merger", 8, 1),(3, "The Convict", 8, 1),(3, "A Benihana Christmas", 8, 1),(3, "Back from Vacation", 8, 1),(3, "Traveling Salesmen", 8, 1),(3, "The Return", 8, 1),(3, "Ben Franklin", 8, 1),(3, "Phyllis' Wedding", 8, 1),(3, "Business School", 8, 1),(3, "Cocktails", 8, 1),(3, "The Negotiation", 8, 1),(3, "Safety Training", 8, 1),(3, "Product Recall", 8, 1),(3, "Women's Appreciation", 8, 1),(3, "Beach Games", 8, 1),(3, "The Job", 8, 1);

INSERT INTO `content` (`season`, `episode`, `tvShow_id`, `user_id`) VALUES 
(1, 'Rites of Passage', 11, 1), (1, 'Wrath of the Northmen', 11, 1), (1, 'Dispossessed', 11, 1), (1, 'Trial', 11, 1), (1, 'Raid', 11, 1), (1, 'Burial of the Dead', 11, 1), (1, "A King's Ransom", 11, 1), (1, 'Sacrifice', 11, 1), (1, 'All Change', 11, 1);

INSERT INTO `content` (`season`, `episode`, `tvShow_id`, `user_id`) VALUES 
(1, 'Rose', 13, 1), (1, 'The End of the World', 13, 1), (1, 'The Uniquiet Dead', 13, 1), (1, 'Aliens of London', 13, 1), (1, 'World War Three', 13, 1), (1, 'Dalek', 13, 1), (1, 'The Long Game', 13, 1), (1, "Father's Day", 13, 1), (1, 'The Empty Child', 13, 1), (1, 'The Doctor Dances', 13, 1), (1, 'Boom Town', 13, 1), (1, 'Bad Wolf', 13, 1), (1, 'The Parting of the Ways', 13, 1);


INSERT INTO `content` (`season`, `episode`, `tvShow_id`, `user_id`) VALUES
(1, 'Pilot', 15, 2), (1, 'Diversity Day', 15, 2), (1, 'Health Care', 15, 2), (1, 'The Alliance', 15, 2), (1, 'Basketball', 15, 2), (1, 'Hot Girl', 15, 2),
(2, "The Dundies", 15, 2), (2, "Sexual Harassment", 15, 2), (2, "Office Olympics", 15, 2), (2, "The Fire", 15, 2), (2, "Halloween", 15, 2), (2, "The Fight", 15, 2), (2, "The Client", 15, 2), (2, "Performance Review", 15, 2), (2, "Email Surveilance", 15, 2), (2, "Christmas Party", 15, 2), (2, "Booze Cruise", 15, 2),
(3, "Gay Witch Hunt", 15, 2), (3, "The Convention", 15, 2), (3, "The Coup", 15, 2), (3, "Grief Conseling", 15, 2), (3, "Initiation", 15, 2), (3, "Diwali", 15, 2),(3, "Branch Closing", 15, 2),(3, "The Merger", 15, 2),(3, "The Convict", 15, 2),(3, "A Benihana Christmas", 15, 2),(3, "Back from Vacation", 15, 2),(3, "Traveling Salesmen", 15, 2),(3, "The Return", 15, 2),(3, "Ben Franklin", 15, 2),(3, "Phyllis' Wedding", 15, 2),(3, "Business School", 15, 2),(3, "Cocktails", 15, 2),(3, "The Negotiation", 15, 2),(3, "Safety Training", 15, 2),(3, "Product Recall", 15, 2),(3, "Women's Appreciation", 15, 2),(3, "Beach Games", 15, 2),(3, "The Job", 15, 2);

INSERT INTO `content` (`season`, `episode`, `tvShow_id`, `user_id`) VALUES 
(1, 'Rites of Passage', 18, 2), (1, 'Wrath of the Northmen', 18, 2), (1, 'Dispossessed', 18, 2), (1, 'Trial', 18, 2), (1, 'Raid', 18, 2), (1, 'Burial of the Dead', 18, 2), (1, "A King's Ransom", 18, 2), (1, 'Sacrifice', 18, 2), (1, 'All Change', 18, 2);

INSERT INTO `content` (`season`, `episode`, `tvShow_id`, `user_id`) VALUES 
(1, 'Rose', 20, 2), (1, 'The End of the World', 20, 2), (1, 'The Uniquiet Dead', 20, 2), (1, 'Aliens of London', 20, 2), (1, 'World War Three', 20, 2), (1, 'Dalek', 20, 2), (1, 'The Long Game', 20, 2), (1, "Father's Day", 20, 2), (1, 'The Empty Child', 20, 2), (1, 'The Doctor Dances', 20, 2), (1, 'Boom Town', 20, 2), (1, 'Bad Wolf', 20, 2), (1, 'The Parting of the Ways', 20, 2);
