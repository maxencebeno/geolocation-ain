-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 05 Avril 2016 à 13:42
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `geolocation`
--

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adresse` longtext COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code_postal` int(11) NOT NULL,
  `ville` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `tel` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `date_creation` datetime NOT NULL,
  `date_modification` datetime DEFAULT NULL,
  `siret` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `kbis` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `url` longtext COLLATE utf8_unicode_ci,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `username_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `email_canonical` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL,
  `salt` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `last_login` datetime DEFAULT NULL,
  `locked` tinyint(1) NOT NULL,
  `expired` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  `confirmation_token` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password_requested_at` datetime DEFAULT NULL,
  `roles` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `credentials_expired` tinyint(1) NOT NULL,
  `credentials_expire_at` datetime DEFAULT NULL,
  `name` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date_creation_entreprise` datetime DEFAULT NULL,
  `description` longtext COLLATE utf8_unicode_ci,
  `rna` longtext COLLATE utf8_unicode_ci,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D64992FC23A8` (`username_canonical`),
  UNIQUE KEY `UNIQ_8D93D649A0D96FBF` (`email_canonical`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Contenu de la table `user`
--

INSERT INTO `user` (`id`, `adresse`, `email`, `code_postal`, `ville`, `tel`, `date_creation`, `date_modification`, `siret`, `kbis`, `url`, `username`, `username_canonical`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `locked`, `expired`, `expires_at`, `confirmation_token`, `password_requested_at`, `roles`, `credentials_expired`, `credentials_expire_at`, `name`, `date_creation_entreprise`, `description`, `rna`, `latitude`, `longitude`) VALUES
(2, '76 rue bossuet', 'test@test.com', 69006, 'LYON', NULL, '2016-01-06 17:31:06', NULL, 'test', 'Canon-in-D-Arr.-Lee-Galloway-Piano-Lee-Galloway.pdf', NULL, 'test', 'test', 'test@test.com', 0, 'e8moi6bcevwwsg40gogkwgw4gs4gsoc', 'eaVYSmUCPdJtI7oq/lbgkUHHq7eTPgMos8bLckiR2wTQDQqBAor/nRLocO/t1BmmdyMOkXOZDH/Ws0cIaTy4gQ==', '2016-01-06 17:31:27', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'test', NULL, 'Entreprise de recyclage', NULL, NULL, NULL),
(3, 'test', 'maxence@maxence.com', 543453, 'test', '0606060606', '2016-01-14 16:44:41', NULL, 'test', NULL, 'http://www.toto.fr', 'maxence', 'maxence', 'maxence@maxence.com', 1, 'tejee7o5hhwo80w4cg08k8wkk80s4ks', 'N6VCpi6CcTVFYDZ3ym6i43h2jvYowv4FJog0MZqXRpqCzEjImboDpDhR1sKyU9j/WCHCifrwTCnRKc8SVt/Alw==', '2016-01-15 06:25:24', 0, 0, NULL, NULL, NULL, 'a:1:{i:0;s:16:"ROLE_SUPER_ADMIN";}', 0, NULL, 'Maxence Beno', NULL, 'Entreprise de recyclage', NULL, NULL, NULL),
(4, '5 rue lazare Carnot', 'vanessa@gmail.com', 1000, 'Bourg-en-Bresse', NULL, '2016-06-01 00:00:00', NULL, '1561651651', NULL, NULL, 'Vanessa', 'vanessa', 'vanessa@gmail.com', 1, 'oraek6fas2880scgo80c4g88wk084g0', 'LZCd9inMghyr/HCjCy1DtM2vHuQFOfcRDgTnGsSoj6NXDAi8X+nnuOF83LIU4vL+sGhj+6GX49OLa0fDslAlsA==', '2016-03-30 08:20:10', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'Vanessa', '2016-01-07 00:00:00', 'Ceci est une page de test 2', NULL, NULL, NULL),
(5, '71 rue Peter Fink', 'geo@gmail.com', 1000, 'Bourg-en-Bresse', NULL, '2016-03-30 11:25:57', NULL, '00000000000', 'Analyse Doodle_Lehnert-Vanessa_Nallet-Judicaelle_Ouali-Farida_Baillivy-gregory.pdf', 'http://geolocation.dev/app_dev.php/', 'Georic', 'georic', 'geo@gmail.com', 1, 'h9h0zqzs968s0g4koso0c4c400cc848', 'akMIL8TalocE4KsQ7fiJZKo685rS51hWtOl1zJViDWQh2vh2aeIJiCxmOV/++h1y2R0M+50a29fuuBQXJm6BEQ==', '2016-04-04 08:51:38', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'GEORIC', '2015-10-30 00:00:00', NULL, NULL, 46.2153648, 5.2415621),
(6, '78 RUE OLIVIER DE SERRES', 'test1@gmail.com', 75015, 'Paris', NULL, '2016-04-04 16:51:51', NULL, '00000000000', NULL, NULL, 'test2', 'test2', 'test1@gmail.com', 1, '20regoj0v65ckwk0ccwokkgsgw0wkgs', '1g5dXHPYMzv6NSEYQlXwt5ZT17XUih4iGob70aqE2IyWx3m/kmUvaGloiE/1DIHl7vtsSsDq4ESmBayo1PsEqg==', '2016-04-04 16:53:41', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'Orange', '1999-11-30 00:00:00', NULL, NULL, 48.8332122, 2.2942155),
(7, '12 RUE DUPHOT', 'test3@gmail.com', 75001, 'Paris', NULL, '2016-04-04 17:00:32', NULL, '000000000000', NULL, NULL, 'test3', 'test3', 'test3@gmail.com', 1, 'dnb33pbcbvkg880kgwg4ggw48sscw88', 'UDp1kpn3XEIbrDUXPLOai7x1iBIIC7qbGT8PW37pxkuqnXhzO8mBegjr7+CmQMRDujUAA/mPOwgx471/5wHszA==', '2016-04-04 17:02:05', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'CHANEL COORDINATION', '1999-11-30 00:00:00', NULL, NULL, 48.8682547, 2.3257654),
(8, '235 ROUTE DE THIL', 'test4@gamil.com', 1120, 'MONTLUEL', NULL, '2016-04-04 17:08:01', NULL, '000000', NULL, NULL, 'test4', 'test4', 'test4@gamil.com', 1, 'hn1wn26b34g8ssgc8s80ck00swo8k8o', 'oGqB+mmTHCVQbtphB/P/KXsFWbK0M4FJhUxCz5PY1N4XovVgILCZkSz5Mf7XCZt9FAkGbHuuTnyoUxAKmGm/gA==', '2016-04-04 17:11:49', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'CARRIER', '1999-11-30 00:00:00', NULL, NULL, 45.8461656, 5.0511076),
(9, '8 COURS DE VERDUN', 'test5@gmail.Com', 1100, 'Oyonnax', NULL, '2016-04-04 17:09:48', NULL, '00000', NULL, NULL, 'test5', 'test5', 'test5@gmail.com', 1, '5uccamqosq4ogo04cc8cs4c0cowc40c', 'saW2EqSJAM4RUHgGQ5CxbEJCPHEGuU2rgBo7kK+s/IGEQdlej1MyAXNWHJI1xY+j9HUxtGMPlXjw0y59jjY/Cw==', '2016-04-04 17:11:20', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'EMIN LEYDIER', '1999-11-30 00:00:00', NULL, NULL, 46.269637, 5.6587532),
(10, 'RD 933', 'test6@gmlklk.com', 1570, 'MANZIAT', NULL, '2016-04-04 17:19:51', NULL, '00000', NULL, NULL, 'test6', 'test6', 'test6@gmlklk.com', 1, 'ljces7l26jkgw8w0kk0swokc88wswcs', 'g/AlzboP5bKeMqBI7nKAH2PfAGixbtPQDDVy+jwFRXGED6RHnOGYDan5Bc062EPyh/VWg7lS4/hFEXLKTySzkA==', '2016-04-04 17:23:21', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'TERRE DE FRANCE', '1999-11-30 00:00:00', NULL, NULL, 46.3411153, 4.8939765),
(11, '285 IMPASSE DES PEUPLIERS', 'test7@gmail.com', 1100, 'BELLIGNAT', NULL, '2016-04-04 17:20:46', NULL, '000', NULL, NULL, 'test7', 'test7', 'test7@gmail.com', 1, 'irqdoy017g0sw0ocwowsgocwo8os8o8', 'EGn2WlKP6ik3763OjF0TK76qjG+9OS9yh4jQ2lVHfJPZPFf6fTU/yryN2PJpztbNosuRHSfOV7txj8Dg0mPwxw==', '2016-04-04 17:23:31', 0, 0, NULL, NULL, NULL, 'a:0:{}', 0, NULL, 'CONCEPTION REALISATION PLASTIQUE ADDUXI', '1999-11-30 00:00:00', NULL, NULL, 46.23864, 5.6226065);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
