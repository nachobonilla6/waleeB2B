-- Script completo para agregar campos contacto_empresa y access_code a la tabla suppliers
-- Ejecuta este script en tu base de datos

-- ============================================
-- 1. Agregar campo contacto_empresa
-- ============================================
SET @column_exists_contacto = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'suppliers' 
    AND COLUMN_NAME = 'contacto_empresa'
);

SET @sql_contacto = IF(@column_exists_contacto = 0,
    'ALTER TABLE `suppliers` ADD COLUMN `contacto_empresa` VARCHAR(255) NULL DEFAULT NULL AFTER `name`',
    'SELECT "El campo contacto_empresa ya existe" AS message'
);

PREPARE stmt_contacto FROM @sql_contacto;
EXECUTE stmt_contacto;
DEALLOCATE PREPARE stmt_contacto;

-- ============================================
-- 2. Agregar campo access_code
-- ============================================
SET @column_exists_access = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'suppliers' 
    AND COLUMN_NAME = 'access_code'
);

SET @sql_access = IF(@column_exists_access = 0,
    'ALTER TABLE `suppliers` ADD COLUMN `access_code` VARCHAR(4) NULL DEFAULT NULL AFTER `contacto_empresa`',
    'SELECT "El campo access_code ya existe" AS message'
);

PREPARE stmt_access FROM @sql_access;
EXECUTE stmt_access;
DEALLOCATE PREPARE stmt_access;

-- ============================================
-- 3. Verificar que los campos se agregaron correctamente
-- ============================================
SELECT 
    COLUMN_NAME, 
    DATA_TYPE, 
    IS_NULLABLE, 
    COLUMN_DEFAULT,
    ORDINAL_POSITION
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'suppliers' 
AND COLUMN_NAME IN ('contacto_empresa', 'access_code')
ORDER BY ORDINAL_POSITION;

