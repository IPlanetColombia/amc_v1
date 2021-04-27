CREATE TABLE IF NOT EXISTS `amc_v4`.`parametro` (
  `id_parametro` INT(11) NOT NULL AUTO_INCREMENT,
  `id_tecnica` INT(10) UNSIGNED NOT NULL,
  `par_nombre` VARCHAR(45) NOT NULL,
  `par_estado` enum('Activo','Inactivo') NOT NULL DEFAULT 'Activo',
  `par_descripcion` VARCHAR(100) NULL DEFAULT '',
  `par_irca` VARCHAR(20) NOT NULL COMMENT 'valor de penalizacion irca',
  PRIMARY KEY (`id_parametro`),
  UNIQUE INDEX `id_parametro_UNIQUE` (`id_parametro` ASC),
  INDEX `fk_tecnica_a_parametro_idx` (`id_tecnica` ASC),
  CONSTRAINT `fk_tecnica_a_parametro`
    FOREIGN KEY (`id_tecnica`)
    REFERENCES `amc_v2`.`tecnica` (`id_tecnica`)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB
AUTO_INCREMENT = 360
DEFAULT CHARACTER SET = utf8
COMMENT = 'Informacion hacerca de los parametro microbiologicos';

--
-- Dumping data for table `parametro`
INSERT INTO `parametro` (`id_parametro`, `id_tecnica`, `par_nombre`, `par_estado`, `par_descripcion`, `par_irca`) VALUES (7,2,'mes','Inactivo','Aerobios Mesófilos (UFC / g o ml) ICMSF 2000 Vol.1 Método 1','');