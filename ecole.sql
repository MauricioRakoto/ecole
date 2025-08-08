/*
SQLyog Ultimate v12.4.3 (64 bit)
MySQL - 5.7.40 : Database - ecole
*********************************************************************
*/

/*!40101 SET NAMES utf8 */;

/*!40101 SET SQL_MODE=''*/;

/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;
CREATE DATABASE /*!32312 IF NOT EXISTS*/`ecole` /*!40100 DEFAULT CHARACTER SET latin1 */;

USE `ecole`;

/*Table structure for table `absences` */

DROP TABLE IF EXISTS `absences`;

CREATE TABLE `absences` (
  `absences_id` int(10) NOT NULL AUTO_INCREMENT,
  `eleve_id` int(10) NOT NULL,
  `classe_id` int(11) DEFAULT NULL,
  `matiere_id` int(10) NOT NULL,
  `minutes` int(11) NOT NULL,
  `date_absence` date NOT NULL,
  PRIMARY KEY (`absences_id`),
  KEY `id_eleve` (`eleve_id`),
  KEY `id_matiere` (`matiere_id`)
) ENGINE=MyISAM AUTO_INCREMENT=24 DEFAULT CHARSET=latin1;

/*Data for the table `absences` */

insert  into `absences`(`absences_id`,`eleve_id`,`classe_id`,`matiere_id`,`minutes`,`date_absence`) values 
(8,2,3,5,60,'2025-08-06'),
(7,1,3,1,60,'2025-08-06'),
(12,2,3,2,20,'2025-08-06'),
(13,4,3,1,120,'2025-08-06'),
(9,1,3,5,20,'2025-08-06'),
(11,1,3,2,60,'2025-08-06'),
(14,1,3,4,120,'2025-08-06'),
(15,2,3,4,60,'2025-08-06'),
(16,4,3,2,60,'2025-08-06'),
(17,1,3,4,30,'2025-08-05'),
(18,5,4,1,30,'2025-08-06'),
(19,5,4,1,60,'2025-08-06'),
(20,6,4,1,60,'2025-08-06'),
(21,7,4,1,60,'2025-08-06'),
(22,5,4,4,120,'2025-08-06'),
(23,5,4,2,30,'2025-08-06');

/*Table structure for table `classes` */

DROP TABLE IF EXISTS `classes`;

CREATE TABLE `classes` (
  `classe_id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_classe` varchar(100) NOT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `annee_debut` year(4) DEFAULT NULL,
  `annee_fin` year(4) DEFAULT NULL,
  `salle` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `classes` */

insert  into `classes`(`classe_id`,`nom_classe`,`niveau`,`annee_debut`,`annee_fin`,`salle`) values 
(3,'1Q21','1',2025,2026,'101'),
(4,'1Q30','1',2025,2026,'101'),
(5,'3G20','1',2025,2026,'101'),
(6,'Classe1','Seconde',2025,2026,'101'),
(7,'1G20','1',2025,2026,'5'),
(8,'3G21','3',2025,2026,'1'),
(9,'TT1','1',2025,2026,'6');

/*Table structure for table `cours` */

DROP TABLE IF EXISTS `cours`;

CREATE TABLE `cours` (
  `cours_id` int(11) NOT NULL AUTO_INCREMENT,
  `classe_id` int(11) DEFAULT NULL,
  `matiere_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`cours_id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=latin1;

/*Data for the table `cours` */

insert  into `cours`(`cours_id`,`classe_id`,`matiere_id`) values 
(19,3,5),
(18,3,3),
(17,3,1),
(16,3,4),
(20,4,1),
(21,4,5),
(22,4,4),
(23,4,6),
(24,4,2);

/*Table structure for table `eleves` */

DROP TABLE IF EXISTS `eleves`;

CREATE TABLE `eleves` (
  `eleve_id` int(10) NOT NULL AUTO_INCREMENT,
  `numero` int(10) DEFAULT NULL,
  `nom_eleve` varchar(100) DEFAULT NULL,
  `prenom_eleve` varchar(100) DEFAULT NULL,
  `sexe_eleve` varchar(50) DEFAULT NULL,
  `date_naissance` date DEFAULT NULL,
  `lieu_naissance` varchar(50) DEFAULT NULL,
  `status` varchar(50) DEFAULT NULL,
  `adresse` varchar(50) DEFAULT NULL,
  `tel_eleve` varchar(50) DEFAULT NULL,
  `etablissement_orign` varchar(50) DEFAULT NULL,
  `annee_scolaire` varchar(50) DEFAULT NULL,
  `date_inscrit` date DEFAULT NULL,
  `nom_pere` varchar(100) DEFAULT NULL,
  `profession_pere` varchar(50) DEFAULT NULL,
  `tel_pere` varchar(50) DEFAULT NULL,
  `nom_mere` varchar(100) DEFAULT NULL,
  `profession_mere` varchar(50) DEFAULT NULL,
  `tel_mere` varchar(50) DEFAULT NULL,
  `nom_tuteur` varchar(100) DEFAULT NULL,
  `profession_tuteur` varchar(50) DEFAULT NULL,
  `tel_tuteur` varchar(50) DEFAULT NULL,
  `classe_id` int(10) DEFAULT NULL,
  PRIMARY KEY (`eleve_id`),
  KEY `classe_id` (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `eleves` */

insert  into `eleves`(`eleve_id`,`numero`,`nom_eleve`,`prenom_eleve`,`sexe_eleve`,`date_naissance`,`lieu_naissance`,`status`,`adresse`,`tel_eleve`,`etablissement_orign`,`annee_scolaire`,`date_inscrit`,`nom_pere`,`profession_pere`,`tel_pere`,`nom_mere`,`profession_mere`,`tel_mere`,`nom_tuteur`,`profession_tuteur`,`tel_tuteur`,`classe_id`) values 
(1,1,'ANDRIAMAHAFANDRIANA','Rabenjamina Irin\'Antonni','GarÃ§on','2025-06-22','Antananarivo','renvoyer','Lot ','033','LTPA','2025 - 2026','2025-06-22','','',NULL,'ANDRIATSIMIALAVAHOAKA Maminiaina Jackie','mÃ©nagÃ¨re','0320236467','','','',3),
(2,2,'BOTONIRINA','Lunneil Dumont','GarÃ§on','2025-06-22','Antananarivo','Passant','Lot ','033','LTPA','2025 - 2026','2025-06-22','BOTONIRINA Tsivoantsiny','militaire',NULL,'RASOATINA Elicia','mÃ©nagÃ¨re','0344503513','','','',3),
(3,NULL,'AINAHARIVAY Andrianasana','Lucas Miaro','GarÃ§on','2025-08-02','a','Passant','Lot','032','LTPA','2025 - 2026','2025-07-28','RANDRIATSITRATRANIHAFA Harivahy','vendeur',NULL,'RAZAZATIANA Andrianatoanadro','vendeuse','0346658067','','','',5),
(4,3,'AINANDRIANINA','Fanahy Ryo','GarÃ§on','2025-08-02','ANKADIFOTSY','passant','Lot','032','LTPA','2025 - 2026','2025-08-02','RANDRIAMBELOSON DesirÃ©','chauufeur',NULL,'Razafindrabe tricia laurence','vendeuse','0340707111','RANDRIANASOLO Narindra','cuisiniÃ¨re','0322634045',3),
(5,1,'ANDRIAMANAMPISOA','Fredis Thomas','GarÃ§on','2006-03-06','Anosibe','Passant','Lot ','0','LTPA','2025 - 2026','2025-08-06','RAKOTONDRAVELO Kean Fidel','Cultivateur',NULL,'RAZAFIMANAMPISOA Marie A','cultivatrice','0338704561','','','',4),
(6,2,'ANDRIAMANANTSOA','Mitia Cristiana','GarÃ§on','2010-03-10',' Befelatanana','Passant','Lot ','033','LTPA','2025 - 2026','2025-08-06','RAKOTOARIMANANA DonnÃ©','mpivarotra',NULL,'ANDRIATSIMIALAVAHOAKA Maminiaina Jackie','mÃ©nagÃ¨re','0344503513','','','',4),
(7,3,'ANDRIAMITANTSOA','Finaritra Cynthia','GarÃ§on','2009-05-31','Anosizato','Passant','Lot ','033','LTPA','2025 - 2026','2025-08-06','ANDRIAMANANTSOA Max Houson','agent commercial',NULL,'RAFARALAHIJAONA Yolande','CommerÃ§ante','0330641585','','','',4),
(8,4,'ANDRIAMPARANY','Mamiarinala Ritha','GarÃ§on','2009-08-24','Ankadivoribe Sud','Passant','Lot ','033','LTPA','2025 - 2026','2025-08-06','ANDRIAMPARANY Dany','Chauffeur',NULL,'RAMILISON Vololoniaina Dina Harinosy','mÃ©nagÃ¨re','0381419997','','','',4),
(9,NULL,'ANDRIAMAHAFANDRIANA','Rabenjamina Irin\'Antonni','GarÃ§on','2025-08-07','Anosizato','Passant','Lot ','033','LTPA','2025 - 2026','2025-08-07','','',NULL,'ANDRIATSIMIALAVAHOAKA Maminiaina Jackie','cultivatrice','0330641585','','','',9);

/*Table structure for table `matieres` */

DROP TABLE IF EXISTS `matieres`;

CREATE TABLE `matieres` (
  `matiere_id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_matiere` varchar(100) NOT NULL,
  `coefficient` int(5) DEFAULT NULL,
  `classe` int(10) DEFAULT NULL,
  PRIMARY KEY (`matiere_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

/*Data for the table `matieres` */

insert  into `matieres`(`matiere_id`,`nom_matiere`,`coefficient`,`classe`) values 
(1,'Anglais',2,NULL),
(2,'Malagasy',2,NULL),
(3,'FranÃ§ais',2,NULL),
(4,'SVT',2,NULL),
(5,'Histo Geo',2,NULL),
(6,'EPS',2,NULL),
(7,'Phy Chimie',2,NULL);

/*Table structure for table `responsable` */

DROP TABLE IF EXISTS `responsable`;

CREATE TABLE `responsable` (
  `responsable_id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_responsable` varchar(100) NOT NULL,
  `prenom_responsable` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `compte` varchar(100) NOT NULL,
  `image` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`responsable_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=latin1;

/*Data for the table `responsable` */

insert  into `responsable`(`responsable_id`,`nom_responsable`,`prenom_responsable`,`username`,`password`,`compte`,`image`) values 
(10,'RAKOTO','Lucah','Lucah','$2y$10$gWU3OPV2VQ9hvd51RJ9ea.bDx..fo3xrp9dMIyXa.PHIi9JgXc1Ru','Surveillant','1754486376_HTML.png'),
(11,'RAKOTOARISOA','Andriamparany','Dodo','$2y$10$7eziW2gwoLGxFac/H64SXO5MnPMnCVL/yvFua2HCtoUGxhVoVAjN.','Professeur','1754490793_PHP.png');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
