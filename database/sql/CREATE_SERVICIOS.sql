-- Migration SQL for servicios table
-- Run this SQL if you prefer to run migrations manually

CREATE TABLE IF NOT EXISTS `servicios` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `codigo` varchar(255) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` varchar(255) NOT NULL DEFAULT 'predefinido',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `servicios_codigo_unique` (`codigo`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert default services
INSERT INTO `servicios` (`codigo`, `nombre`, `descripcion`, `tipo`, `activo`, `created_at`, `updated_at`) VALUES
('diseno_web', 'Web Design', 'Professional website design and development, including responsive design, basic SEO optimization, and source code delivery.', 'predefinido', 1, NOW(), NOW()),
('redes_sociales', 'Social Media Management', 'Complete social media management, including content creation, post scheduling, follower interaction, and metrics analysis.', 'predefinido', 1, NOW(), NOW()),
('seo', 'SEO / Positioning', 'Search engine optimization services, including keyword research, on-page optimization, link building, and results analysis.', 'predefinido', 1, NOW(), NOW()),
('publicidad', 'Digital Advertising', 'Digital advertising campaigns on platforms including Google Ads, Facebook Ads, Instagram Ads, and advertising budget management.', 'predefinido', 1, NOW(), NOW()),
('mantenimiento', 'Web Maintenance', 'Continuous website maintenance service, including security updates, backups, monitoring, and technical support.', 'predefinido', 1, NOW(), NOW()),
('hosting', 'Hosting & Domain', 'Web hosting and domain registration services, including hosting, SSL certificates, email, and technical support.', 'predefinido', 1, NOW(), NOW()),
('combo', 'Complete Package', 'Complete package including web design, hosting, domain, social media management, basic SEO, and monthly maintenance.', 'predefinido', 1, NOW(), NOW());

