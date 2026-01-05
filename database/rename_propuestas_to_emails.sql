-- Script SQL para renombrar la tabla propuestas_personalizadas a emails
-- y agregar el campo tipo con valor por defecto 'propuesta_personalizada'

-- Paso 1: Agregar el campo 'tipo' a la tabla propuestas_personalizadas (si no existe)
SET @dbname = DATABASE();
SET @tablename = 'propuestas_personalizadas';
SET @columnname = 'tipo';

SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' VARCHAR(50) DEFAULT ''propuesta_personalizada'' AFTER `id`')
));

PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- Paso 2: Actualizar todos los registros existentes para que tengan el tipo 'propuesta_personalizada'
UPDATE propuestas_personalizadas 
SET tipo = 'propuesta_personalizada' 
WHERE tipo IS NULL OR tipo = '';

-- Paso 3: Renombrar la tabla de propuestas_personalizadas a emails
-- Verificar si la tabla emails ya existe
SET @newtablename = 'emails';
SET @preparedStatement2 = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.TABLES
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @newtablename)
  ) > 0,
  'SELECT 1',
  CONCAT('RENAME TABLE ', @tablename, ' TO ', @newtablename)
));

PREPARE renameIfNotExists FROM @preparedStatement2;
EXECUTE renameIfNotExists;
DEALLOCATE PREPARE renameIfNotExists;

-- Verificar que la tabla se renombró correctamente
SELECT
    TABLE_NAME,
    COLUMN_NAME,
    DATA_TYPE,
    CHARACTER_MAXIMUM_LENGTH,
    COLUMN_DEFAULT,
    IS_NULLABLE
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
    AND TABLE_NAME = 'emails'
ORDER BY ORDINAL_POSITION;

