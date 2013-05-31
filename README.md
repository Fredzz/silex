Silex/Twig example for StarApple
================================

How to install:

1) Clone the repo

2) Create database with the following tables:

CREATE TABLE IF NOT EXISTS `authors` (
  `author_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_name` varchar(160) NOT NULL,
  `email` varchar(160) NOT NULL,
  `bio` text NOT NULL,
  PRIMARY KEY (`author_id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=85 ;

CREATE TABLE IF NOT EXISTS `posts` (
  `post_id` int(11) NOT NULL AUTO_INCREMENT,
  `owner_id` int(11) NOT NULL,
  `title` varchar(160) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`post_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=106 ;


3) Use composer to download all dependencies:
php composer.phar install

4) Edit database details under resources/local.php

5) Open .htaccess and edit the path of RewriteBase to the path of your silex folder
 If silex app is under htdocs/silex_test, then RewriteBase should be :
 RewriteBase /silex_test
 
6) Browse to silexpath/web
