-- Agregar campo contacto_empresa a la tabla suppliers (versión segura)
-- Este script verifica si el campo existe antes de agregarlo

-- Verificar si el campo ya existe
SET @column_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'suppliers' 
    AND COLUMN_NAME = 'contacto_empresa'
);

-- Agregar el campo solo si no existe
SET @sql = IF(@column_exists = 0,
    'ALTER TABLE `suppliers` ADD COLUMN `contacto_empresa` VARCHAR(255) NULL DEFAULT NULL AFTER `name`',
    'SELECT "El campo contacto_empresa ya existe en la tabla suppliers" AS message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verificar que el campo se agregó correctamente
SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'suppliers' 
AND COLUMN_NAME = 'contacto_empresa';

