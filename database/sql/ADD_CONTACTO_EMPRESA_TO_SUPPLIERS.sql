-- Agregar campo contacto_empresa a la tabla suppliers
ALTER TABLE `suppliers` 
ADD COLUMN `contacto_empresa` VARCHAR(255) NULL DEFAULT NULL 
AFTER `name`;

-- Verificar que el campo se agregó correctamente
-- SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE, COLUMN_DEFAULT 
-- FROM INFORMATION_SCHEMA.COLUMNS 
-- WHERE TABLE_SCHEMA = DATABASE() 
-- AND TABLE_NAME = 'suppliers' 
-- AND COLUMN_NAME = 'contacto_empresa';

