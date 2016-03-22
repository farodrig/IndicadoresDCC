-- MySQL Script generated by MySQL Workbench
-- sáb 19 mar 2016 01:34:31 CLT
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema UDashboard
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema UDashboard
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `UDashboard` DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ;
USE `UDashboard` ;

-- -----------------------------------------------------
-- Table `UDashboard`.`Category`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Category` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Categorías en las que se agrupan las métricas',
  `name` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 3
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`OrgType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`OrgType` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) CHARACTER SET 'utf8' NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Organization`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Organization` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Organización tipo árbol de DCC/áreas/unidades',
  `parent` INT(11) NOT NULL DEFAULT '0',
  `type` INT(11) NOT NULL,
  `name` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Tree-org_Tree-org_idx` (`parent` ASC),
  INDEX `fk_Tree-org_Tree_Tipo1_idx` (`type` ASC),
  CONSTRAINT `fk_Tree-org_Tree-org`
    FOREIGN KEY (`parent`)
    REFERENCES `UDashboard`.`Organization` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Tree-org_Tree_Tipo1`
    FOREIGN KEY (`type`)
    REFERENCES `UDashboard`.`OrgType` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 24
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Dashboard`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Dashboard` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `org` INT(11) NOT NULL,
  `title` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`, `org`),
  INDEX `fk_Dashboards_Tree-org1_idx` (`org` ASC),
  CONSTRAINT `fk_dash_org_id`
    FOREIGN KEY (`org`)
    REFERENCES `UDashboard`.`Organization` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 12
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Graphic`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Graphic` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `aggregation_x` BINARY NOT NULL DEFAULT 0,
  `min_year` INT(11) NOT NULL,
  `max_year` INT(11) NOT NULL,
  `position` INT(11) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 30
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`GraphDash`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`GraphDash` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `dashboard` INT(11) NOT NULL,
  `graphic` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `dashboard`, `graphic`),
  INDEX `fk_graficoDashboard_Dashboards1_idx` (`dashboard` ASC),
  INDEX `fk_graficoDashboard_graficos1_idx` (`graphic` ASC),
  CONSTRAINT `fk_graficoDashboard_Dashboards1`
    FOREIGN KEY (`dashboard`)
    REFERENCES `UDashboard`.`Dashboard` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_graficoDashboard_graficos1`
    FOREIGN KEY (`graphic`)
    REFERENCES `UDashboard`.`Graphic` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 24
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`State`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`State` (
  `id` INT(11) NOT NULL,
  `name` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Unit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Unit` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL COMMENT 'tipos de unidades.\nej: nº de papers, $, nº alumnos, cursos, etc…',
  PRIMARY KEY (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 24
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Metric`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Metric` (
  `id` INT(11) NOT NULL AUTO_INCREMENT COMMENT 'Métricas que son creadas con sus respectivas unidades y cotas, para luego poder asociarlas a una  organización en el árbol e ingresar las mediciones.',
  `category` INT(11) NOT NULL,
  `y_unit` INT(11) NOT NULL,
  `y_name` VARCHAR(45) CHARACTER SET 'utf8' NULL,
  `x_unit` INT(11) NOT NULL,
  `x_name` VARCHAR(45) NULL,
  PRIMARY KEY (`id`, `category`, `y_unit`, `x_unit`),
  INDEX `fk_Metrics_Metric-cat1_idx` (`category` ASC),
  INDEX `fk_Metrics_Metric-units1_idx` (`y_unit` ASC),
  INDEX `fk_Metric_Unit1_idx` (`x_unit` ASC),
  CONSTRAINT `fk_Metrics_Metric-cat1`
    FOREIGN KEY (`category`)
    REFERENCES `UDashboard`.`Category` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Metrics_Metric-units1`
    FOREIGN KEY (`y_unit`)
    REFERENCES `UDashboard`.`Unit` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Metric_Unit1`
    FOREIGN KEY (`x_unit`)
    REFERENCES `UDashboard`.`Unit` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 26
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`MetOrg`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`MetOrg` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `org` INT(11) NOT NULL,
  `metric` INT(11) NOT NULL,
  PRIMARY KEY (`id`, `org`, `metric`),
  INDEX `fk_Metric-org_Tree-org1_idx` (`org` ASC),
  INDEX `fk_Metric-org_Metrics1_idx` (`metric` ASC),
  CONSTRAINT `fk_Metric-org_Metrics1`
    FOREIGN KEY (`metric`)
    REFERENCES `UDashboard`.`Metric` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Metric-org_Tree-org`
    FOREIGN KEY (`org`)
    REFERENCES `UDashboard`.`Organization` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 22
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`User` (
  `id` VARCHAR(15) CHARACTER SET 'utf8' NOT NULL COMMENT 'RUT usuario',
  `name` VARCHAR(45) CHARACTER SET 'utf8' NULL DEFAULT NULL,
  `email` VARCHAR(45) NULL DEFAULT NULL,
  `isAdmin` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Value`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Value` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `metorg` INT(11) NOT NULL,
  `state` INT(11) NOT NULL,
  `updater` VARCHAR(15) CHARACTER SET 'utf8' NOT NULL,
  `validator` VARCHAR(15) CHARACTER SET 'utf8' NULL,
  `modified` INT(11) NOT NULL DEFAULT '0',
  `value` DOUBLE NULL DEFAULT NULL,
  `x_value` VARCHAR(50) NULL DEFAULT NULL,
  `target` DOUBLE NULL DEFAULT NULL,
  `expected` DOUBLE NULL DEFAULT NULL,
  `year` INT(11) NULL DEFAULT NULL,
  `dateup` DATETIME NULL,
  `dateval` DATETIME NULL DEFAULT NULL,
  `proposed_value` DOUBLE NULL,
  `proposed_target` DOUBLE NULL DEFAULT NULL,
  `proposed_expected` DOUBLE NULL DEFAULT NULL,
  `proposed_x_value` VARCHAR(50) NULL,
  PRIMARY KEY (`id`, `metorg`, `state`),
  INDEX `fk_Mediciones_Metric-org1_idx` (`metorg` ASC),
  INDEX `fk_Measure_State1_idx` (`state` ASC),
  INDEX `fk_Value_User1_idx` (`updater` ASC),
  INDEX `fk_Value_User2_idx` (`validator` ASC),
  CONSTRAINT `fk_Measure_State1`
    FOREIGN KEY (`state`)
    REFERENCES `UDashboard`.`State` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Mediciones_Metric-org1`
    FOREIGN KEY (`metorg`)
    REFERENCES `UDashboard`.`MetOrg` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Value_User1`
    FOREIGN KEY (`updater`)
    REFERENCES `UDashboard`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Value_User2`
    FOREIGN KEY (`validator`)
    REFERENCES `UDashboard`.`User` (`id`)
    ON DELETE SET NULL
    ON UPDATE CASCADE)
ENGINE = InnoDB
AUTO_INCREMENT = 53
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Position`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Position` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `org` INT(11) NOT NULL,
  `short_name` VARCHAR(50) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Position_Organization1_idx` (`org` ASC),
  CONSTRAINT `fk_Position_Organization1`
    FOREIGN KEY (`org`)
    REFERENCES `UDashboard`.`Organization` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Role`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Role` (
  `user` VARCHAR(15) CHARACTER SET 'utf8' NOT NULL,
  `position` INT NOT NULL,
  `initial_date` DATE NOT NULL,
  `final_date` DATE NULL,
  PRIMARY KEY (`user`, `position`),
  INDEX `fk_Role_Position1_idx` (`position` ASC),
  CONSTRAINT `fk_User_Permits`
    FOREIGN KEY (`user`)
    REFERENCES `UDashboard`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Role_Position1`
    FOREIGN KEY (`position`)
    REFERENCES `UDashboard`.`Position` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`SerieType`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`SerieType` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Aggregation_Type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Aggregation_Type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `type` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Serie`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Serie` (
  `metOrg` INT(11) NOT NULL,
  `graphic` INT(11) NOT NULL,
  `type` INT(11) NOT NULL,
  `year_aggregation` INT NOT NULL,
  `x_aggregation` INT NOT NULL,
  `color` VARCHAR(45) NULL,
  INDEX `fk_MetOrg_has_Graphic_Graphic1_idx` (`graphic` ASC),
  INDEX `fk_MetOrg_has_Graphic_MetOrg1_idx` (`metOrg` ASC),
  INDEX `fk_Serie_SerieType1_idx` (`type` ASC),
  PRIMARY KEY (`metOrg`, `graphic`),
  INDEX `fk_Serie_Aggregation_Type1_idx` (`year_aggregation` ASC),
  INDEX `fk_Serie_Aggregation_Type2_idx` (`x_aggregation` ASC),
  CONSTRAINT `fk_MetOrg_has_Graphic_MetOrg1`
    FOREIGN KEY (`metOrg`)
    REFERENCES `UDashboard`.`MetOrg` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_MetOrg_has_Graphic_Graphic1`
    FOREIGN KEY (`graphic`)
    REFERENCES `UDashboard`.`Graphic` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Serie_SerieType1`
    FOREIGN KEY (`type`)
    REFERENCES `UDashboard`.`SerieType` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Serie_Aggregation_Type1`
    FOREIGN KEY (`year_aggregation`)
    REFERENCES `UDashboard`.`Aggregation_Type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Serie_Aggregation_Type2`
    FOREIGN KEY (`x_aggregation`)
    REFERENCES `UDashboard`.`Aggregation_Type` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`FODA`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`FODA` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `org` INT(11) NOT NULL,
  `year` INT(11) NOT NULL,
  `comment` TEXT NULL,
  `validated` TINYINT(1) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UNIQUE` (`org` ASC, `year` ASC),
  CONSTRAINT `fk_FODA_Organization1`
    FOREIGN KEY (`org`)
    REFERENCES `UDashboard`.`Organization` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Priority`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Priority` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci
COMMENT = 'High, Medium, Low';


-- -----------------------------------------------------
-- Table `UDashboard`.`FODA_Type`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`FODA_Type` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(45) NOT NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Item` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `foda` INT(11) NOT NULL,
  `priority` INT NOT NULL,
  `type` INT NOT NULL,
  `title` VARCHAR(100) NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Item_Priority1_idx` (`priority` ASC),
  INDEX `fk_Item_FODA_Type1_idx` (`type` ASC),
  CONSTRAINT `fk_Item_FODA1`
    FOREIGN KEY (`foda`)
    REFERENCES `UDashboard`.`FODA` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Item_Priority1`
    FOREIGN KEY (`priority`)
    REFERENCES `UDashboard`.`Priority` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Item_FODA_Type1`
    FOREIGN KEY (`type`)
    REFERENCES `UDashboard`.`FODA_Type` (`id`)
    ON DELETE RESTRICT
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Completion_Status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Completion_Status` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `status` VARCHAR(45) NULL,
  PRIMARY KEY (`id`))
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Strategic_Plan`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Strategic_Plan` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `org` INT(11) NOT NULL,
  `year` INT(11) NOT NULL,
  `status` INT NOT NULL,
  `description` TEXT NOT NULL,
  `validated` TINYINT(1) NOT NULL DEFAULT 0,
  `deadline` DATE NULL,
  `comment` TEXT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `UNIQUE` (`org` ASC, `year` ASC),
  INDEX `fk_Strategic_Plan_Completion_Status1_idx` (`status` ASC),
  CONSTRAINT `fk_Strategy_Organization1`
    FOREIGN KEY (`org`)
    REFERENCES `UDashboard`.`Organization` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Strategic_Plan_Completion_Status1`
    FOREIGN KEY (`status`)
    REFERENCES `UDashboard`.`Completion_Status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Goal`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Goal` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `strategy` INT(11) NOT NULL,
  `status` INT NOT NULL,
  `userInCharge` VARCHAR(15) CHARACTER SET 'utf8' NOT NULL,
  `tittle` VARCHAR(50) NOT NULL,
  `validated` TINYINT(1) NOT NULL DEFAULT 0,
  `timestamp` TIMESTAMP NULL,
  `deadline` DATE NULL,
  `description` TEXT NULL,
  `comment` TEXT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Target_User1_idx` (`userInCharge` ASC),
  INDEX `fk_Goal_Completion_Status1_idx` (`status` ASC),
  CONSTRAINT `fk_Target_Strategy1`
    FOREIGN KEY (`strategy`)
    REFERENCES `UDashboard`.`Strategic_Plan` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Target_User1`
    FOREIGN KEY (`userInCharge`)
    REFERENCES `UDashboard`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Goal_Completion_Status1`
    FOREIGN KEY (`status`)
    REFERENCES `UDashboard`.`Completion_Status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Collaborator`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Collaborator` (
  `strategy` INT(11) NOT NULL,
  `user` VARCHAR(15) CHARACTER SET 'utf8' NOT NULL,
  `description` TEXT NULL,
  PRIMARY KEY (`strategy`, `user`),
  INDEX `fk_Strategy_has_User_User1_idx` (`user` ASC),
  INDEX `fk_Strategy_has_User_Strategy1_idx` (`strategy` ASC),
  CONSTRAINT `fk_Strategy_has_User_Strategy1`
    FOREIGN KEY (`strategy`)
    REFERENCES `UDashboard`.`Strategic_Plan` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Strategy_has_User_User1`
    FOREIGN KEY (`user`)
    REFERENCES `UDashboard`.`User` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Action`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Action` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `target` INT(11) NOT NULL,
  `userInCharge` VARCHAR(15) CHARACTER SET 'utf8' NOT NULL,
  `status` INT NOT NULL,
  `tittle` VARCHAR(50) NOT NULL,
  `current_result` TEXT NULL,
  `expected_result` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Action_User1_idx` (`userInCharge` ASC),
  INDEX `fk_Action_Completion_Status1_idx` (`status` ASC),
  CONSTRAINT `fk_Action_Target1`
    FOREIGN KEY (`target`)
    REFERENCES `UDashboard`.`Goal` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Action_User1`
    FOREIGN KEY (`userInCharge`)
    REFERENCES `UDashboard`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Action_Completion_Status1`
    FOREIGN KEY (`status`)
    REFERENCES `UDashboard`.`Completion_Status` (`id`)
    ON DELETE NO ACTION
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Goal_Item`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Goal_Item` (
  `target` INT(11) NOT NULL,
  `item` INT(11) NOT NULL,
  PRIMARY KEY (`target`, `item`),
  INDEX `fk_Target_has_Item_Item1_idx` (`item` ASC),
  INDEX `fk_Target_has_Item_Target1_idx` (`target` ASC),
  CONSTRAINT `fk_Target_has_Item_Target1`
    FOREIGN KEY (`target`)
    REFERENCES `UDashboard`.`Goal` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT `fk_Target_has_Item_Item1`
    FOREIGN KEY (`item`)
    REFERENCES `UDashboard`.`Item` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Function`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Function` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `position` INT NOT NULL,
  `short_name` VARCHAR(50) NOT NULL,
  `description` TEXT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Function_Position1_idx` (`position` ASC),
  CONSTRAINT `fk_Function_Position1`
    FOREIGN KEY (`position`)
    REFERENCES `UDashboard`.`Position` (`id`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


-- -----------------------------------------------------
-- Table `UDashboard`.`Permit`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `UDashboard`.`Permit` (
  `user` VARCHAR(15) CHARACTER SET 'utf8' NOT NULL,
  `org` INT(11) NOT NULL,
  `permit` VARCHAR(500) NULL,
  PRIMARY KEY (`user`, `org`),
  INDEX `fk_User_has_Organization_Organization1_idx` (`org` ASC),
  INDEX `fk_User_has_Organization_User1_idx` (`user` ASC),
  CONSTRAINT `fk_User_has_Organization_User1`
    FOREIGN KEY (`user`)
    REFERENCES `UDashboard`.`User` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_User_has_Organization_Organization1`
    FOREIGN KEY (`org`)
    REFERENCES `UDashboard`.`Organization` (`id`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
DEFAULT CHARACTER SET = utf8
COLLATE = utf8_unicode_ci;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `UDashboard`.`Category`
-- -----------------------------------------------------
START TRANSACTION;
USE `UDashboard`;
INSERT INTO `UDashboard`.`Category` (`id`, `name`) VALUES (1, 'Productividad');
INSERT INTO `UDashboard`.`Category` (`id`, `name`) VALUES (2, 'Finanzas');

COMMIT;


-- -----------------------------------------------------
-- Data for table `UDashboard`.`OrgType`
-- -----------------------------------------------------
START TRANSACTION;
USE `UDashboard`;
INSERT INTO `UDashboard`.`OrgType` (`id`, `name`) VALUES (1, 'Soporte');
INSERT INTO `UDashboard`.`OrgType` (`id`, `name`) VALUES (2, 'Operación');

COMMIT;


-- -----------------------------------------------------
-- Data for table `UDashboard`.`Organization`
-- -----------------------------------------------------
START TRANSACTION;
USE `UDashboard`;
INSERT INTO `UDashboard`.`Organization` (`id`, `parent`, `type`, `name`) VALUES (1, 1, 2, 'DCC');
INSERT INTO `UDashboard`.`Organization` (`id`, `parent`, `type`, `name`) VALUES (0, 0, 1, 'DCC');

COMMIT;


-- -----------------------------------------------------
-- Data for table `UDashboard`.`State`
-- -----------------------------------------------------
START TRANSACTION;
USE `UDashboard`;
INSERT INTO `UDashboard`.`State` (`id`, `name`) VALUES (0, 'no_validado');
INSERT INTO `UDashboard`.`State` (`id`, `name`) VALUES (1, 'validado');
INSERT INTO `UDashboard`.`State` (`id`, `name`) VALUES (-1, 'por_borrar');

COMMIT;


-- -----------------------------------------------------
-- Data for table `UDashboard`.`Priority`
-- -----------------------------------------------------
START TRANSACTION;
USE `UDashboard`;
INSERT INTO `UDashboard`.`Priority` (`id`, `name`) VALUES (1, 'Alta');
INSERT INTO `UDashboard`.`Priority` (`id`, `name`) VALUES (2, 'Media');
INSERT INTO `UDashboard`.`Priority` (`id`, `name`) VALUES (3, 'Baja');

COMMIT;


-- -----------------------------------------------------
-- Data for table `UDashboard`.`FODA_Type`
-- -----------------------------------------------------
START TRANSACTION;
USE `UDashboard`;
INSERT INTO `UDashboard`.`FODA_Type` (`id`, `name`) VALUES (1, 'Fortalezas');
INSERT INTO `UDashboard`.`FODA_Type` (`id`, `name`) VALUES (2, 'Oportunidades');
INSERT INTO `UDashboard`.`FODA_Type` (`id`, `name`) VALUES (3, 'Debilidades');
INSERT INTO `UDashboard`.`FODA_Type` (`id`, `name`) VALUES (4, 'Amenazas');

COMMIT;


-- -----------------------------------------------------
-- Data for table `UDashboard`.`Completion_Status`
-- -----------------------------------------------------
START TRANSACTION;
USE `UDashboard`;
INSERT INTO `UDashboard`.`Completion_Status` (`id`, `status`) VALUES (1, 'Finalizado');
INSERT INTO `UDashboard`.`Completion_Status` (`id`, `status`) VALUES (2, 'Realizando');
INSERT INTO `UDashboard`.`Completion_Status` (`id`, `status`) VALUES (3, 'Fuera de Plazo');

COMMIT;

