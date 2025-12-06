-- Script SQL para eliminar las tablas de n8n directamente de la base de datos
-- Ejecutar en la base de datos de producción si es necesario

-- Eliminar tablas de n8n
DROP TABLE IF EXISTS `n8n_posts`;
DROP TABLE IF EXISTS `n8n_errors`;
DROP TABLE IF EXISTS `n8n_bots`;

-- Verificar que las tablas fueron eliminadas
-- SELECT TABLE_NAME FROM information_schema.TABLES WHERE TABLE_SCHEMA = 'tu_base_de_datos' AND TABLE_NAME LIKE 'n8n%';

