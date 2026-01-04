-- Script SQL para agregar cliente_id a rproductos y webhook_productos a clientes_en_proceso
-- Ejecutar este script directamente en la base de datos si la migración no funciona

-- 1. Agregar cliente_id a rproductos (si no existe)
SET @dbname = DATABASE();
SET @tablename = 'rproductos';
SET @columnname = 'cliente_id';
SET @preparedStatement = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename)
      AND (COLUMN_NAME = @columnname)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename, ' ADD COLUMN ', @columnname, ' BIGINT UNSIGNED NULL AFTER `id`, ADD CONSTRAINT `rproductos_cliente_id_foreign` FOREIGN KEY (`cliente_id`) REFERENCES `clientes_en_proceso` (`id`) ON DELETE CASCADE')
));
PREPARE alterIfNotExists FROM @preparedStatement;
EXECUTE alterIfNotExists;
DEALLOCATE PREPARE alterIfNotExists;

-- 2. Agregar webhook_productos a clientes_en_proceso (si no existe)
SET @tablename2 = 'clientes_en_proceso';
SET @columnname2 = 'webhook_productos';
SET @preparedStatement2 = (SELECT IF(
  (
    SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS
    WHERE
      (TABLE_SCHEMA = @dbname)
      AND (TABLE_NAME = @tablename2)
      AND (COLUMN_NAME = @columnname2)
  ) > 0,
  'SELECT 1',
  CONCAT('ALTER TABLE ', @tablename2, ' ADD COLUMN ', @columnname2, ' VARCHAR(255) NULL AFTER `webhook_url`')
));
PREPARE alterIfNotExists2 FROM @preparedStatement2;
EXECUTE alterIfNotExists2;
DEALLOCATE PREPARE alterIfNotExists2;

-- Verificar que las columnas se crearon correctamente
SELECT 
    TABLE_NAME,
    COLUMN_NAME,
    DATA_TYPE,
    IS_NULLABLE,
    COLUMN_DEFAULT
FROM INFORMATION_SCHEMA.COLUMNS
WHERE TABLE_SCHEMA = DATABASE()
    AND (
        (TABLE_NAME = 'rproductos' AND COLUMN_NAME = 'cliente_id')
        OR (TABLE_NAME = 'clientes_en_proceso' AND COLUMN_NAME = 'webhook_productos')
    );

