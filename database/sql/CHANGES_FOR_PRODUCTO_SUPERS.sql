-- Script SQL para los cambios necesarios para usar producto_supers en lugar de rproductos
-- Ejecutar estos comandos en orden

-- 1. Agregar campo cliente_id a producto_supers (si no existe)
SET @column_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.COLUMNS 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'producto_supers' 
    AND COLUMN_NAME = 'cliente_id'
);

SET @sql = IF(@column_exists = 0,
    'ALTER TABLE `producto_supers` ADD COLUMN `cliente_id` BIGINT UNSIGNED NULL AFTER `id`',
    'SELECT "El campo cliente_id ya existe en producto_supers" AS message'
);

PREPARE stmt FROM @sql;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- 2. Agregar foreign key constraint (si no existe)
SET @fk_exists = (
    SELECT COUNT(*) 
    FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE 
    WHERE TABLE_SCHEMA = DATABASE() 
    AND TABLE_NAME = 'producto_supers' 
    AND CONSTRAINT_NAME = 'producto_supers_cliente_id_foreign'
);

SET @sql_fk = IF(@fk_exists = 0,
    'ALTER TABLE `producto_supers` ADD CONSTRAINT `producto_supers_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE',
    'SELECT "La foreign key ya existe" AS message'
);

PREPARE stmt_fk FROM @sql_fk;
EXECUTE stmt_fk;
DEALLOCATE PREPARE stmt_fk;

-- Verificar que los campos necesarios existen
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = DATABASE() 
AND TABLE_NAME = 'producto_supers'
AND COLUMN_NAME IN ('cliente_id', 'stock', 'cantidad', 'categoria', 'activo', 'nombre', 'descripcion')
ORDER BY ORDINAL_POSITION;

