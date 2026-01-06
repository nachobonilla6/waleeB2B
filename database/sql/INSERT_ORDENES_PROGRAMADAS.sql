-- ============================================
-- SQL PARA CREAR LA TABLA ordenes_programadas
-- ============================================
-- Ejecuta este SQL directamente en tu base de datos MySQL/MariaDB

CREATE TABLE IF NOT EXISTS `ordenes_programadas` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `tipo` enum('extraccion_clientes','emails_automaticos') NOT NULL COMMENT 'Tipo de orden programada',
  `activo` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Si la orden está activa',
  `recurrencia_horas` decimal(5,2) DEFAULT NULL COMMENT 'Recurrencia en horas (0.5, 1, 2, etc.)',
  `last_run` timestamp NULL DEFAULT NULL COMMENT 'Última vez que se ejecutó',
  `configuracion` json DEFAULT NULL COMMENT 'Configuración adicional en JSON',
  `user_id` bigint(20) unsigned DEFAULT NULL COMMENT 'Usuario que creó la orden',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_tipo` (`tipo`),
  KEY `idx_activo` (`activo`),
  KEY `idx_last_run` (`last_run`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================
-- VERIFICAR QUE SE CREÓ CORRECTAMENTE
-- ============================================
-- Ejecuta esto para verificar:
-- DESCRIBE ordenes_programadas;
-- O
-- SHOW CREATE TABLE ordenes_programadas;

-- ============================================
-- EJEMPLOS DE USO
-- ============================================

-- 1. Crear una orden de extracción de clientes
-- INSERT INTO ordenes_programadas 
-- (tipo, activo, recurrencia_horas, last_run, user_id, created_at, updated_at)
-- VALUES 
-- ('extraccion_clientes', 1, 0.5, NULL, 1, NOW(), NOW());

-- 2. Crear una orden de emails automáticos
-- INSERT INTO ordenes_programadas 
-- (tipo, activo, recurrencia_horas, last_run, user_id, created_at, updated_at)
-- VALUES 
-- ('emails_automaticos', 1, 1, NULL, 1, NOW(), NOW());

-- 3. Ver todas las órdenes activas
-- SELECT * FROM ordenes_programadas WHERE activo = 1;

-- 4. Ver órdenes pendientes (para n8n)
-- SELECT *
-- FROM ordenes_programadas
-- WHERE activo = 1
-- AND (
--   last_run IS NULL
--   OR last_run <= NOW() - INTERVAL 30 MINUTE
-- );

-- 5. Actualizar last_run después de ejecutar
-- UPDATE ordenes_programadas
-- SET last_run = NOW()
-- WHERE id = 1;

-- 6. Desactivar una orden
-- UPDATE ordenes_programadas
-- SET activo = 0
-- WHERE id = 1;

-- 7. Eliminar una orden (si es necesario)
-- DELETE FROM ordenes_programadas WHERE id = 1;



