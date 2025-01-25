CREATE TABLE IF NOT EXISTS `PREFIX_productvideoloops`
(
    `id` INT(11) UNSIGNED NOT NULL UNIQUE,
    `filename`  VARCHAR(255) NOT NULL DEFAULT '',
    `date_add`   DATETIME         NOT NULL,
    `date_upd`   DATETIME         NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;

CREATE TABLE IF NOT EXISTS `PREFIX_productvideoloops_attribute` (
    `id` INT(11) UNSIGNED NOT NULL UNIQUE,
    `filename` VARCHAR(255) NOT NULL DEFAULT '',
    `date_add` DATETIME NOT NULL,
    `date_upd` DATETIME NOT NULL,
    PRIMARY KEY(`id`)
) ENGINE = ENGINE_TYPE DEFAULT CHARSET = utf8;
