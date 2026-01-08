-- Agregar campos de inventario a la tabla producto_supers
-- Estos campos permiten gestionar el inventario completo de productos

-- Agregar campo cantidad (cantidad total en inventario)
ALTER TABLE `producto_supers` 
ADD COLUMN `cantidad` INT NOT NULL DEFAULT 0 AFTER `stock`;

-- Agregar campo fecha_entrada (fecha en que el producto entró al inventario)
ALTER TABLE `producto_supers` 
ADD COLUMN `fecha_entrada` DATE NULL AFTER `cantidad`;

-- Agregar campo fecha_limite_venta (fecha límite para vender el producto)
ALTER TABLE `producto_supers` 
ADD COLUMN `fecha_limite_venta` DATE NULL AFTER `fecha_entrada`;

-- Agregar campo fecha_salida (fecha en que el producto salió del inventario)
ALTER TABLE `producto_supers` 
ADD COLUMN `fecha_salida` DATE NULL AFTER `fecha_limite_venta`;

-- Agregar campo foto_qr (ruta o URL de la foto del código QR)
ALTER TABLE `producto_supers` 
ADD COLUMN `foto_qr` VARCHAR(255) NULL AFTER `imagen`;

