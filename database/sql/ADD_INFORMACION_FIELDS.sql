-- Script SQL para agregar campos de información a suppliers y producto_supers
-- Ejecutar estos comandos en orden

-- ============================================
-- 1. AGREGAR CAMPO INFORMACION A SUPPLIERS
-- ============================================
-- Verificar si el campo ya existe antes de agregarlo
SET @column_exists_suppliers = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'suppliers' 
    AND COLUMN_NAME = 'informacion'
);

SET @sql_suppliers = IF(@column_exists_suppliers = 0,
    'ALTER TABLE `suppliers` ADD COLUMN `informacion` TEXT NULL AFTER `nota`',
    'SELECT "El campo informacion ya existe en suppliers" AS message'
);

PREPARE stmt_suppliers FROM @sql_suppliers;
EXECUTE stmt_suppliers;
DEALLOCATE PREPARE stmt_suppliers;

-- ============================================
-- 2. AGREGAR CAMPOS DE INFORMACIÓN A PRODUCTO_SUPERS
-- ============================================

-- Service consomateur
SET @column_exists_service = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'producto_supers' 
    AND COLUMN_NAME = 'service_consomateur'
);

SET @sql_service = IF(@column_exists_service = 0,
    'ALTER TABLE `producto_supers` ADD COLUMN `service_consomateur` TEXT NULL AFTER `descripcion`',
    'SELECT "El campo service_consomateur ya existe en producto_supers" AS message'
);

PREPARE stmt_service FROM @sql_service;
EXECUTE stmt_service;
DEALLOCATE PREPARE stmt_service;

-- Recetter ou outre
SET @column_exists_recetter = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'producto_supers' 
    AND COLUMN_NAME = 'recetter_ou_outre'
);

SET @sql_recetter = IF(@column_exists_recetter = 0,
    'ALTER TABLE `producto_supers` ADD COLUMN `recetter_ou_outre` TEXT NULL AFTER `service_consomateur`',
    'SELECT "El campo recetter_ou_outre ya existe en producto_supers" AS message'
);

PREPARE stmt_recetter FROM @sql_recetter;
EXECUTE stmt_recetter;
DEALLOCATE PREPARE stmt_recetter;

-- Tracabilidad
SET @column_exists_tracabilidad = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'producto_supers' 
    AND COLUMN_NAME = 'tracabilidad'
);

SET @sql_tracabilidad = IF(@column_exists_tracabilidad = 0,
    'ALTER TABLE `producto_supers` ADD COLUMN `tracabilidad` TEXT NULL AFTER `recetter_ou_outre`',
    'SELECT "El campo tracabilidad ya existe en producto_supers" AS message'
);

PREPARE stmt_tracabilidad FROM @sql_tracabilidad;
EXECUTE stmt_tracabilidad;
DEALLOCATE PREPARE stmt_tracabilidad;

-- Inform et valorise le produit
SET @column_exists_inform_valorise = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'producto_supers' 
    AND COLUMN_NAME = 'inform_et_valorise'
);

SET @sql_inform_valorise = IF(@column_exists_inform_valorise = 0,
    'ALTER TABLE `producto_supers` ADD COLUMN `inform_et_valorise` TEXT NULL AFTER `tracabilidad`',
    'SELECT "El campo inform_et_valorise ya existe en producto_supers" AS message'
);

PREPARE stmt_inform_valorise FROM @sql_inform_valorise;
EXECUTE stmt_inform_valorise;
DEALLOCATE PREPARE stmt_inform_valorise;

-- ============================================
-- 3. VERIFICAR QUE LOS CAMPOS SE AGREGARON CORRECTAMENTE
-- ============================================

-- Verificar campos en suppliers
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'suppliers'
AND COLUMN_NAME = 'informacion';

-- Verificar campos en producto_supers
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'producto_supers'
AND COLUMN_NAME IN ('service_consomateur', 'recetter_ou_outre', 'tracabilidad', 'inform_et_valorise')
ORDER BY ORDINAL_POSITION;

