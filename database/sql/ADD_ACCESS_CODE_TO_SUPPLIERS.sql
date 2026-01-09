-- Agregar campo access_code a la tabla suppliers
ALTER TABLE `suppliers` 
ADD COLUMN `access_code` VARCHAR(4) NULL DEFAULT NULL 
AFTER `contacto_empresa`;

-- Verificar que el campo se agregó correctamente
-- SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
-- AND TABLE_NAME = 'suppliers' 
-- AND COLUMN_NAME = 'access_code';

