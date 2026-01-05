-- Script SQL para verificar si el campo 'idioma' existe en la tabla 'clientes_en_proceso'
-- Ejecutar este script directamente en la base de datos

-- Verificar si la columna existe
SELECT 
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH,
    IS_NULLABLE,
    COLUMN_DEFAULT,
    COLUMN_TYPE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'clientes_en_proceso'
    AND COLUMN_NAME = 'idioma';

-- Si no existe, ejecutar este comando para agregarlo:
-- ALTER TABLE `clientes_en_proceso` ADD COLUMN `idioma` VARCHAR(10) NULL AFTER `ciudad`;

