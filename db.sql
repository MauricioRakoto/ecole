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

/*Table structure for table `classes` */

DROP TABLE IF EXISTS `classes`;

CREATE TABLE `classes` (
  `classe_id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_classe` varchar(100) NOT NULL,
  `niveau` varchar(50) DEFAULT NULL,
  `annee_debut` year(4) DEFAULT NULL,
  `annee_fin` year(4) DEFAULT NULL,
  `salle` varchar(50) DEFAULT NULL,
  `matiere` int(10) DEFAULT NULL,
  PRIMARY KEY (`classe_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

/*Data for the table `classes` */

insert  into `classes`(`classe_id`,`nom_classe`,`niveau`,`annee_debut`,`annee_fin`,`salle`,`matiere`) values 
(3,'1Q21','1',2025,2026,'101',NULL),
(4,'1Q30','1',2025,2026,'101',NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

/*Data for the table `eleves` */

insert  into `eleves`(`eleve_id`,`numero`,`nom_eleve`,`prenom_eleve`,`sexe_eleve`,`date_naissance`,`lieu_naissance`,`status`,`adresse`,`tel_eleve`,`etablissement_orign`,`annee_scolaire`,`date_inscrit`,`nom_pere`,`profession_pere`,`tel_pere`,`nom_mere`,`profession_mere`,`tel_mere`,`nom_tuteur`,`profession_tuteur`,`tel_tuteur`,`classe_id`) values 
(1,1,'ANDRIAMAHAFANDRIANA','Rabenjamina Irin\'Antonni','GarÃ§on','2025-06-22','Antananarivo','Passant','Lot ','033','LTPA','2025 - 2026','2025-06-22','','',NULL,'ANDRIATSIMIALAVAHOAKA Maminiaina Jackie','mÃ©nagÃ¨re','0320236467','','','',3),
(2,2,'BOTONIRINA','Lunneil Dumont','GarÃ§on','2025-06-22','Antananarivo','Passant','Lot ','033','LTPA','2025 - 2026','2025-06-22','BOTONIRINA Tsivoantsiny','militaire',NULL,'RASOATINA Elicia','mÃ©nagÃ¨re','0344503513','','','',3);

/*Table structure for table `matieres` */

DROP TABLE IF EXISTS `matieres`;

CREATE TABLE `matieres` (
  `matiere_id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_matiere` varchar(100) NOT NULL,
  `coefficient` int(5) DEFAULT NULL,
  PRIMARY KEY (`matiere_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

/*Data for the table `matieres` */

/*Table structure for table `responsable` */

DROP TABLE IF EXISTS `responsable`;

CREATE TABLE `responsable` (
  `responsable_id` int(10) NOT NULL AUTO_INCREMENT,
  `nom_responsable` varchar(100) NOT NULL,
  `prenom_responsable` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(100) DEFAULT NULL,
  `compte` varchar(100) NOT NULL,
  PRIMARY KEY (`responsable_id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

/*Data for the table `responsable` */

insert  into `responsable`(`responsable_id`,`nom_responsable`,`prenom_responsable`,`username`,`password`,`compte`) values 
(5,'RAKOTO','Lucah','Lucah','$2y$10$wd584dj5TUcIgFz7HaAaCelP68gEWUFW5DO1885H4ibrSC5uhGFvG','Surveillant'),
(6,'RAKOTO','Mauricio','Mauricio','$2y$10$wcSiHjISVqPG/rkuzMevLeIU34nR5EswYqkXDmwGVYjUHiZyJwiqy','Professeur'),
(7,'RAKOTO','Stanic','Stanic','$2y$10$IBC/x83YvQCXFdnLXf5HMemIIgU.Gp1Tl0ndcZXo05Qs/DJ/OLdem','Surveillant'),
(8,'RAKOTO','Stan','Stan','$2y$10$4Z/ikCR2U9V3iJbaNigXWO.TyRGrycjNHRZjkP/RGzuG/tpvy5oYG','Surveillant'),
(9,'RAKOTO','StanC','StanC','$2y$10$a301KSQ./CZUzbVv.7BzZefMuE17LehMICUC3WqPNm/FsYG0FD5wK','Surveillant');

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;
