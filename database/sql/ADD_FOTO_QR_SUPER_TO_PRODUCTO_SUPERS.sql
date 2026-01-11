-- Script SQL para agregar campo foto_qr_super a la tabla producto_supers
-- Este campo es para un segundo código QR que se usa en el super para pegarlo a los productos

-- Verificar si el campo ya existe antes de agregarlo
SET @column_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'producto_supers' 
    AND COLUMN_NAME = 'foto_qr_super'
);

SET @sql = IF(@column_exists = 0,
    'ALTER TABLE `producto_supers` ADD COLUMN `foto_qr_super` VARCHAR(255) NULL AFTER `foto_qr`',
    'SELECT "El campo foto_qr_super ya existe en producto_supers" AS message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verificar que el campo se agregó correctamente
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'producto_supers'
AND COLUMN_NAME = 'foto_qr_super';

