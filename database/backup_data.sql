-- Datos iniciales del sistema
-- Este archivo solo inserta datos, las tablas ya están creadas en 01-schema.sql

USE `inventario_db`;

-- Volcado de datos para la tabla `usuarios`
INSERT INTO `usuarios` (`id`, `username`, `password`, `nombre`, `rol`, `email`, `created_at`, `estado_tmp`) VALUES
(1, 'jesus', '$2y$12$phBUc8cXrM2y/qw7rAIR..rpeG1.3TWfiIGpRS1/hTmpE3ZlESi36', 'Administrador', 'admin', 'admin@example.com', '2025-11-16 16:52:56', 'activo'),
(5, 'mauricio', '$2y$12$SL7jbF8AnDDXy/Rq09m.WuqsN58mKM/JeBRIUxxY4QtDMF1Uv6Fqa', 'Auxiliar', 'auxiliar', NULL, '2025-11-23 09:46:52', 'activo'),
(8, 'paisana', '$2y$12$Y0CqegaVf9xW3qM8W3PWHOKnVjwiidHxzWxieqEn72TukI9WidIpq', 'pedro', 'analista', NULL, '2025-11-24 15:41:13', 'inactivo')
ON DUPLICATE KEY UPDATE username=username;

-- Volcado de datos para la tabla `proveedores`
INSERT INTO `proveedores` (`id`, `nombre`, `contacto`, `telefono`, `direccion`, `estado`) VALUES
(1, 'Proveedor Alpha', 'Juan', '916105634', 'Av. Alamos con Revolución', 'activo')
ON DUPLICATE KEY UPDATE nombre=nombre;
