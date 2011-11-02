-- phpMyAdmin SQL Dump
-- version 2.10.3
-- http://www.phpmyadmin.net
-- 
-- Host: localhost
-- Erstellungszeit: 02. November 2011 um 11:09
-- Server Version: 5.0.77
-- PHP-Version: 5.2.9

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

-- 
-- Datenbank: `30608_everycook`
-- 
DROP DATABASE `30608_everycook`;
CREATE DATABASE `30608_everycook` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci;
USE `30608_everycook`;

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `actions`
-- 

CREATE TABLE `actions` (
  `ACT_ID` int(11) NOT NULL auto_increment,
  `PRF_UID` int(11) default NULL,
  `ACT_CREATED` date NOT NULL,
  `ACT_CHANGED` date default NULL,
  `ACT_PICTURE` mediumblob,
  `ACT_PICTURE_AUTH` varchar(30) collate utf8_unicode_ci default NULL,
  `ACT_DESC_EN` longtext collate utf8_unicode_ci,
  `ACT_DESC_DE` longtext collate utf8_unicode_ci,
  PRIMARY KEY  (`ACT_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `actions`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `courses`
-- 

CREATE TABLE `courses` (
  `COU_ID` int(11) NOT NULL auto_increment,
  `COU_DESC` longtext collate utf8_unicode_ci,
  `COU_SERVINGS` float default NULL,
  `COU_NO` int(11) default NULL,
  `COU_RECIPES` longtext collate utf8_unicode_ci,
  `COU_PROCEDURE` longtext collate utf8_unicode_ci,
  PRIMARY KEY  (`COU_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `courses`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ecology`
-- 

CREATE TABLE `ecology` (
  `ECO_ID` int(11) NOT NULL auto_increment,
  `ECO_DESC_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `ECO_DESC_DE` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ECO_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Daten für Tabelle `ecology`
-- 

INSERT INTO `ecology` (`ECO_ID`, `ECO_DESC_EN`, `ECO_DESC_DE`) VALUES (1, 'Sustainable source', 'Nachhaltige quelle');
INSERT INTO `ecology` (`ECO_ID`, `ECO_DESC_EN`, `ECO_DESC_DE`) VALUES (2, 'Overused source', 'Übernutze Quelle');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ethical_criteria`
-- 

CREATE TABLE `ethical_criteria` (
  `ETH_ID` int(11) NOT NULL auto_increment,
  `ETH_DESC_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `ETH_DESC_DE` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`ETH_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- 
-- Daten für Tabelle `ethical_criteria`
-- 

INSERT INTO `ethical_criteria` (`ETH_ID`, `ETH_DESC_EN`, `ETH_DESC_DE`) VALUES (1, 'Kosher', 'Koscher');
INSERT INTO `ethical_criteria` (`ETH_ID`, `ETH_DESC_EN`, `ETH_DESC_DE`) VALUES (2, 'Halal', 'Halal');
INSERT INTO `ethical_criteria` (`ETH_ID`, `ETH_DESC_EN`, `ETH_DESC_DE`) VALUES (3, 'Vegan', 'Vegan');
INSERT INTO `ethical_criteria` (`ETH_ID`, `ETH_DESC_EN`, `ETH_DESC_DE`) VALUES (4, 'Not classified', 'Nicht klassifiziert');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `group_names`
-- 

CREATE TABLE `group_names` (
  `GRP_ID` int(11) NOT NULL auto_increment,
  `GRP_DESC_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `GRP_DESC_DE` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`GRP_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Daten für Tabelle `group_names`
-- 

INSERT INTO `group_names` (`GRP_ID`, `GRP_DESC_EN`, `GRP_DESC_DE`) VALUES (1, 'Meat', '');
INSERT INTO `group_names` (`GRP_ID`, `GRP_DESC_EN`, `GRP_DESC_DE`) VALUES (2, 'Seafood', '');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ingredient_conveniences`
-- 

CREATE TABLE `ingredient_conveniences` (
  `CONV_ID` int(11) NOT NULL auto_increment,
  `CONV_DESC_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `CONV_DESC_DE` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`CONV_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

-- 
-- Daten für Tabelle `ingredient_conveniences`
-- 

INSERT INTO `ingredient_conveniences` (`CONV_ID`, `CONV_DESC_EN`, `CONV_DESC_DE`) VALUES (1, 'No Convenience', '');
INSERT INTO `ingredient_conveniences` (`CONV_ID`, `CONV_DESC_EN`, `CONV_DESC_DE`) VALUES (2, 'Pealed, Cooked, Vacuumized', '');
INSERT INTO `ingredient_conveniences` (`CONV_ID`, `CONV_DESC_EN`, `CONV_DESC_DE`) VALUES (3, 'Canned', '');
INSERT INTO `ingredient_conveniences` (`CONV_ID`, `CONV_DESC_EN`, `CONV_DESC_DE`) VALUES (4, 'Precooked for rewarm', '');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ingredient_states`
-- 

CREATE TABLE `ingredient_states` (
  `STATE_ID` int(11) NOT NULL auto_increment,
  `STATE_DESC_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `STATE_DESC_DE` varchar(100) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`STATE_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Daten für Tabelle `ingredient_states`
-- 

INSERT INTO `ingredient_states` (`STATE_ID`, `STATE_DESC_EN`, `STATE_DESC_DE`) VALUES (1, 'Fresh', NULL);
INSERT INTO `ingredient_states` (`STATE_ID`, `STATE_DESC_EN`, `STATE_DESC_DE`) VALUES (2, 'Deep Frozen', NULL);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `ingredients`
-- 

CREATE TABLE `ingredients` (
  `ING_ID` int(11) NOT NULL auto_increment,
  `PRF_UID` int(11) default NULL,
  `ING_CREATED` date NOT NULL,
  `ING_CHANGED` date default NULL,
  `NUT_ID` int(11) default NULL,
  `ING_GROUP` int(11) NOT NULL,
  `ING_SUBGROUP` int(11) NOT NULL,
  `ING_STATE` int(11) NOT NULL,
  `ING_CONVENIENCE` int(11) NOT NULL,
  `ING_STORABILITY` int(11) NOT NULL,
  `ING_DENSITY` float default NULL,
  `ING_PICTURE` mediumblob,
  `ING_PICTURE_AUTH` varchar(30) collate utf8_unicode_ci default NULL,
  `ING_TITLE_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `ING_TITLE_DE` varchar(100) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`ING_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `ingredients`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `interface_menu`
-- 

CREATE TABLE `interface_menu` (
  `IME_LANG` varchar(3) collate utf8_unicode_ci NOT NULL,
  `IME_LANGNAME` varchar(50) collate utf8_unicode_ci NOT NULL,
  `IME_LANGSEL` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_SETTINGS` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_LOGIN` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FLUSER` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FLPASS` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_REGISTER` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_SENT` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FRVORN` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FRNACHN` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FRUSER` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FREMAIL` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FRPASS` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FRPASST` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FREVORN` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FRENACHN` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FREUSER` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FREEMAIL` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FREPASS` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FREPASST` varchar(200) collate utf8_unicode_ci NOT NULL,
  `IME_FRESENT` varchar(200) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`IME_LANG`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Daten für Tabelle `interface_menu`
-- 

INSERT INTO `interface_menu` (`IME_LANG`, `IME_LANGNAME`, `IME_LANGSEL`, `IME_SETTINGS`, `IME_LOGIN`, `IME_FLUSER`, `IME_FLPASS`, `IME_REGISTER`, `IME_SENT`, `IME_FRVORN`, `IME_FRNACHN`, `IME_FRUSER`, `IME_FREMAIL`, `IME_FRPASS`, `IME_FRPASST`, `IME_FREVORN`, `IME_FRENACHN`, `IME_FREUSER`, `IME_FREEMAIL`, `IME_FREPASS`, `IME_FREPASST`, `IME_FRESENT`) VALUES ('EN', 'English', 'Language', 'Settings', 'Login', 'Username:', 'Password:', 'Register', 'Login', 'Firstname:', 'Lastename:', 'Username:', 'E-Mail:', 'Password:', 'Again Password:', 'only letters alowed', 'only letters alowed', 'only letters and numbers alowed', 'invalid email adress', 'min. 6 sighns,', 'passwords not the same', 'please fill all fields');
INSERT INTO `interface_menu` (`IME_LANG`, `IME_LANGNAME`, `IME_LANGSEL`, `IME_SETTINGS`, `IME_LOGIN`, `IME_FLUSER`, `IME_FLPASS`, `IME_REGISTER`, `IME_SENT`, `IME_FRVORN`, `IME_FRNACHN`, `IME_FRUSER`, `IME_FREMAIL`, `IME_FRPASS`, `IME_FRPASST`, `IME_FREVORN`, `IME_FRENACHN`, `IME_FREUSER`, `IME_FREEMAIL`, `IME_FREPASS`, `IME_FREPASST`, `IME_FRESENT`) VALUES ('DE', 'Deutsch', 'Sprache', 'Einstellungen', 'Login', 'Benutzername:', 'Passwort:', 'Registrieren', 'Einloggen', 'Vorname:', 'Nachname:', 'Benutzername:', 'E-Mail:', 'Passwort:', 'Passwort wiederholen:', 'Nur Buchstaben erlaubt', 'Nur Buchstaben erlaubt', 'Nur Buchstaben und Zahlen erlaubt', 'Ungültige Email Adresse', 'min. 6 zeichen', 'Passwörter sind nich identisch', 'Bitte alle Felder ausfüllen');
INSERT INTO `interface_menu` (`IME_LANG`, `IME_LANGNAME`, `IME_LANGSEL`, `IME_SETTINGS`, `IME_LOGIN`, `IME_FLUSER`, `IME_FLPASS`, `IME_REGISTER`, `IME_SENT`, `IME_FRVORN`, `IME_FRNACHN`, `IME_FRUSER`, `IME_FREMAIL`, `IME_FRPASS`, `IME_FRPASST`, `IME_FREVORN`, `IME_FRENACHN`, `IME_FREUSER`, `IME_FREEMAIL`, `IME_FREPASS`, `IME_FREPASST`, `IME_FRESENT`) VALUES ('FR', 'Français', 'Langue', 'Réglages', 'Login', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `interface_textes`
-- 

CREATE TABLE `interface_textes` (
  `ITE_VIEW` int(11) NOT NULL,
  `ITE_LANG` varchar(3) collate utf8_unicode_ci NOT NULL,
  `ITE_BOTL_CONTENT` varchar(200) collate utf8_unicode_ci default NULL,
  `ITE_BOTL_ONCLICK` varchar(20) collate utf8_unicode_ci default NULL,
  `ITE_BOTM_CONTENT` varchar(200) collate utf8_unicode_ci default NULL,
  `ITE_BOTM_ONCLICK` varchar(20) collate utf8_unicode_ci default NULL,
  `ITE_BOTR_CONTENT` varchar(200) collate utf8_unicode_ci default NULL,
  `ITE_BOTR_ONCLICK` varchar(20) collate utf8_unicode_ci default NULL,
  `ITE_MIDL` longtext collate utf8_unicode_ci,
  `ITE_MIDR` longtext collate utf8_unicode_ci,
  `ITE_W_MIDR` int(11) NOT NULL,
  PRIMARY KEY  (`ITE_VIEW`,`ITE_LANG`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='saves the interface textes in all languages';

-- 
-- Daten für Tabelle `interface_textes`
-- 

INSERT INTO `interface_textes` (`ITE_VIEW`, `ITE_LANG`, `ITE_BOTL_CONTENT`, `ITE_BOTL_ONCLICK`, `ITE_BOTM_CONTENT`, `ITE_BOTM_ONCLICK`, `ITE_BOTR_CONTENT`, `ITE_BOTR_ONCLICK`, `ITE_MIDL`, `ITE_MIDR`, `ITE_W_MIDR`) VALUES (0, 'EN', 'Search Recipe', 'ShowView(''1'')', 'Search Food', 'ShowView(''2'')', 'The Cooking Machine', 'ShowView(''3'')', '<div id="index_div_mf_tt">Welcome to Everycook the new way to prepare meals. You will cook better food in less time and even know more about what you eat.</div><a href="#" OnClick="ShowView(''4'')"><div id="index_div_pymn"><div class="index_text_middle"><div>Plan Your Meal Now</div></div></div></a>', '<img src=""></img>', 55);
INSERT INTO `interface_textes` (`ITE_VIEW`, `ITE_LANG`, `ITE_BOTL_CONTENT`, `ITE_BOTL_ONCLICK`, `ITE_BOTM_CONTENT`, `ITE_BOTM_ONCLICK`, `ITE_BOTR_CONTENT`, `ITE_BOTR_ONCLICK`, `ITE_MIDL`, `ITE_MIDR`, `ITE_W_MIDR`) VALUES (0, 'DE', 'Rezept Suchen', 'ShowView(''1'')', 'Essen Suchen', 'ShowView(''2'')', 'Die Kochende Maschiene', 'ShowView(''3'')', '<div id="index_div_mf_tt">Willkommen bei Everycook, der neuen Methode essen zu zubereiten. Sie werden besseres essen in weniger zeit kochen und mehr darüber erfahren was sie eigentlich essen.</div><a href="#" OnClick="ShowView(''4'')"><div id="index_div_pymn"><div class="index_text_middle"><div>Planen Sie Ihre Mahlzeiten Jetzt</div></div></div></a>', '<img src=""></img>', 55);
INSERT INTO `interface_textes` (`ITE_VIEW`, `ITE_LANG`, `ITE_BOTL_CONTENT`, `ITE_BOTL_ONCLICK`, `ITE_BOTM_CONTENT`, `ITE_BOTM_ONCLICK`, `ITE_BOTR_CONTENT`, `ITE_BOTR_ONCLICK`, `ITE_MIDL`, `ITE_MIDR`, `ITE_W_MIDR`) VALUES (0, 'FR', 'Recette de recherche', 'ShowView(''1'')', 'Recherche alimentaire', 'ShowView(''2'')', 'la machine bouillante', 'ShowView(''3'')', '<div id="index_div_mf_tt">Bienvenue Everycook la nouvelle façon de préparer les repas. Vous cuisinerez meilleure nourriture en moins de temps et même en savoir plus sur ce que vous mangez.</div><a href="#" OnClick="ShowView(''4'')"><div id="index_div_pymn"><div class="index_text_middle"><div>Planifier votre repas maintenant</div></div></div></a>', '<img src=""></img>', 55);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `meals`
-- 

CREATE TABLE `meals` (
  `MEA_ID` int(11) NOT NULL auto_increment,
  `MEA_DATE` date default NULL,
  `MEA_TYPE` varchar(100) collate utf8_unicode_ci default NULL,
  `PRF_UID` int(11) default NULL,
  `MEA_COURSES` longtext collate utf8_unicode_ci,
  PRIMARY KEY  (`MEA_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `meals`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `pro_to_prd`
-- 

CREATE TABLE `pro_to_prd` (
  `PRO_ID` int(11) NOT NULL,
  `PRD_ID` int(11) NOT NULL,
  PRIMARY KEY  (`PRO_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Daten für Tabelle `pro_to_prd`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `pro_to_sto`
-- 

CREATE TABLE `pro_to_sto` (
  `PRO_ID` int(11) NOT NULL,
  `STO_ID` int(11) NOT NULL,
  PRIMARY KEY  (`PRO_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Daten für Tabelle `pro_to_sto`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `producers`
-- 

CREATE TABLE `producers` (
  `PRD_ID` int(11) NOT NULL auto_increment,
  `PRD_NAME` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`PRD_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `producers`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `products`
-- 

CREATE TABLE `products` (
  `PRO_ID` int(11) NOT NULL auto_increment,
  `PRO_BARCODE` int(11) default NULL,
  `PRO_PACKAGE_GRAMMS` int(11) default NULL,
  `ING_ID` int(11) default NULL,
  `PRO_ECO` int(11) NOT NULL,
  `PRO_ETHIC` int(11) NOT NULL,
  `PRO_PICTURE` mediumblob,
  `PRO_PICTURE_COPYR` varchar(30) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`PRO_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `products`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `profiles`
-- 

CREATE TABLE `profiles` (
  `PRF_UID` int(11) NOT NULL auto_increment,
  `PRF_FIRSTNAME` varchar(100) collate utf8_unicode_ci default NULL,
  `PRF_LASTNAME` varchar(100) collate utf8_unicode_ci default NULL,
  `PRF_NICK` varchar(100) collate utf8_unicode_ci default NULL,
  `PRF_EMAIL` varchar(100) collate utf8_unicode_ci default NULL,
  `PRF_PW` varchar(256) collate utf8_unicode_ci default NULL,
  `PRF_LOC_GPS` varchar(100) collate utf8_unicode_ci default NULL,
  `PRF_LIKES_I` longtext collate utf8_unicode_ci,
  `PRF_LIKES_R` longtext collate utf8_unicode_ci,
  `PRF_NOTLIKES_I` longtext collate utf8_unicode_ci,
  `PRF_NOTLIKES_R` longtext collate utf8_unicode_ci,
  `PRF_SHOPLISTS` longtext collate utf8_unicode_ci,
  PRIMARY KEY  (`PRF_UID`),
  UNIQUE KEY `PRF_EMAIL` (`PRF_EMAIL`),
  UNIQUE KEY `PRF_NICK` (`PRF_NICK`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

-- 
-- Daten für Tabelle `profiles`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `recipes`
-- 

CREATE TABLE `recipes` (
  `REC_ID` int(11) NOT NULL auto_increment,
  `REC_CREATED` date NOT NULL,
  `REC_CHANGED` date default NULL,
  `REC_PICTURE` mediumblob,
  `REC_PICTURE_AUTH` varchar(30) collate utf8_unicode_ci default NULL,
  `REC_TITLE_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `REC_TITLE_DE` varchar(100) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`REC_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `recipes`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `shoplists`
-- 

CREATE TABLE `shoplists` (
  `SHO_ID` int(11) NOT NULL auto_increment,
  `SHO_DATE` date NOT NULL,
  `SHO_PRODUCTS` longtext collate utf8_unicode_ci,
  `SHO_QUANTITIES` longtext collate utf8_unicode_ci,
  PRIMARY KEY  (`SHO_ID`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- 
-- Daten für Tabelle `shoplists`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `step_types`
-- 

CREATE TABLE `step_types` (
  `STT_ID` int(11) NOT NULL auto_increment,
  `STT_DESC_EN` longtext collate utf8_unicode_ci NOT NULL,
  `STT_DESC_DE` longtext collate utf8_unicode_ci,
  PRIMARY KEY  (`STT_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Daten für Tabelle `step_types`
-- 

INSERT INTO `step_types` (`STT_ID`, `STT_DESC_EN`, `STT_DESC_DE`) VALUES (1, 'Put in water.', NULL);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `steps`
-- 

CREATE TABLE `steps` (
  `REC_ID` int(11) NOT NULL,
  `ACT_ID` int(11) default NULL,
  `ING_ID` int(11) default NULL,
  `STE_STEP_NO` int(11) NOT NULL,
  `STE_GRAMS` int(11) default NULL,
  `STE_T_BOTTOM` int(11) default NULL,
  `STE_T_LID` int(11) default NULL,
  `STE_T_STEAM` int(11) default NULL,
  `STE_BAR` float default NULL,
  `STE_RPM` int(11) default NULL,
  `STE_CLOCKWISE` tinyint(1) default NULL,
  `STE_STIR_RUN` int(11) default NULL,
  `STE_STIR_PAUSE` int(11) default NULL,
  `STE_STEP_DURATION` int(11) default NULL,
  `STT_ID` int(11) default NULL,
  PRIMARY KEY  (`REC_ID`,`STE_STEP_NO`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- 
-- Daten für Tabelle `steps`
-- 


-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `storability`
-- 

CREATE TABLE `storability` (
  `STORAB_ID` int(11) NOT NULL auto_increment,
  `STORAB_DESC_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `STORAB_DESC_DE` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`STORAB_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- 
-- Daten für Tabelle `storability`
-- 

INSERT INTO `storability` (`STORAB_ID`, `STORAB_DESC_EN`, `STORAB_DESC_DE`) VALUES (1, 'Less than 3 days chilled', 'Weniger als 3 Tage gekühlt');
INSERT INTO `storability` (`STORAB_ID`, `STORAB_DESC_EN`, `STORAB_DESC_DE`) VALUES (2, '1-2 weeks chilled', '1-2 Wochen gekühlt');

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `stores`
-- 

CREATE TABLE `stores` (
  `STO_ID` int(11) NOT NULL auto_increment,
  `STO_LOC_GPS` varchar(100) collate utf8_unicode_ci default NULL,
  `STO_LOC_ADDR` varchar(200) collate utf8_unicode_ci default NULL,
  `SUP_ID` int(11) NOT NULL,
  PRIMARY KEY  (`STO_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Daten für Tabelle `stores`
-- 

INSERT INTO `stores` (`STO_ID`, `STO_LOC_GPS`, `STO_LOC_ADDR`, `SUP_ID`) VALUES (1, '(40.3N,45,3W)', 'MyStreet 123 1234 MyCity', 1);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `subgroup_names`
-- 

CREATE TABLE `subgroup_names` (
  `SUBGRP_ID` int(11) NOT NULL auto_increment,
  `SUBGRP_OF` int(11) NOT NULL,
  `SUBGRP_DESC_EN` varchar(100) collate utf8_unicode_ci NOT NULL,
  `SUBGRP_DESC_DE` varchar(100) collate utf8_unicode_ci default NULL,
  PRIMARY KEY  (`SUBGRP_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Daten für Tabelle `subgroup_names`
-- 

INSERT INTO `subgroup_names` (`SUBGRP_ID`, `SUBGRP_OF`, `SUBGRP_DESC_EN`, `SUBGRP_DESC_DE`) VALUES (1, 0, 'Chicken', NULL);

-- --------------------------------------------------------

-- 
-- Tabellenstruktur für Tabelle `suppliers`
-- 

CREATE TABLE `suppliers` (
  `SUP_ID` int(11) NOT NULL auto_increment,
  `SUP_NAME` varchar(100) collate utf8_unicode_ci NOT NULL,
  PRIMARY KEY  (`SUP_ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

-- 
-- Daten für Tabelle `suppliers`
-- 

INSERT INTO `suppliers` (`SUP_ID`, `SUP_NAME`) VALUES (1, 'MySupplier');
