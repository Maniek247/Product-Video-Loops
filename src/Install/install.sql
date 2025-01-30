CREATE TABLE IF NOT EXISTS `PREFIX_productvideoloops`
(
    `id_product` INT(11) UNSIGNED NOT NULL UNIQUE,
    `filename`  VARCHAR(255) NOT NULL DEFAULT '',
    `cover`     TINYINT(1)        NOT NULL,
    `position`  INT NOT NULL DEFAULT '1',
    `date_add`  DATETIME         NOT NULL,
    `date_upd`  DATETIME         NOT NULL,
    PRIMARY KEY(`id_product`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_productvideoloops_attribute` (
    `id_product` INT(11) UNSIGNED NOT NULL UNIQUE,
    `filename`  VARCHAR(255) NOT NULL DEFAULT '',
    `cover`     TINYINT(1)        NOT NULL,
    `position`  INT NOT NULL DEFAULT '1',
    `date_add`  DATETIME         NOT NULL,
    `date_upd`  DATETIME         NOT NULL,
    PRIMARY KEY(`id_product`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;
