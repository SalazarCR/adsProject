USE inventario_db;

-- ==========================================
-- 1. INSERTAR PROVEEDORES (Shell y Castrol)
-- ==========================================
INSERT INTO proveedores (nombre, contacto, telefono, direccion, estado) VALUES 
('Shell Distribución Perú', 'Jorge Ventas', '999111000', 'Av. Argentina 456, Callao', 'activo'),
('Castrol BP', 'Maria Logística', '999222333', 'Av. Javier Prado 1234, Lima', 'activo'),
('Importaciones Generales', 'Pedro Almacén', '999888777', 'Jr. Puno 101, Cercado', 'activo');

-- ==========================================
-- 2. INSERTAR CATEGORÍAS
-- ==========================================
INSERT INTO categorias (nombre, descripcion, estado) VALUES 
('Aceites Motor Diesel 15W-40', 'Lubricantes multigrado para trabajo pesado', 'activo'),
('Refrigerantes', 'Líquidos refrigerantes y anticongelantes (Coolants)', 'activo'),
('Transmisión y Engranajes', 'Aceites para cajas y diferenciales', 'activo');

-- ==========================================
-- 3. INSERTAR PRODUCTOS (Carga Masiva)
-- ==========================================
-- Nota: Asumimos ID 1=Shell, ID 2=Castrol. ID 1=Aceites, ID 2=Refrigerantes.

INSERT INTO productos (codigo_interno, nombre, descripcion, precio_venta, categoria_id, proveedor_id, estado) VALUES 
-- SHELL ROTELLA
('SH-ROT-T1', 'Shell Rotella T1 15W-40', 'Aceite convencional de calidad', 45.00, 1, 1, 'activo'),
('SH-ROT-T3', 'Shell Rotella T3 Fleet 15W-40', 'Para flotas mixtas', 48.00, 1, 1, 'activo'),
('SH-ROT-T4', 'Shell Rotella T4 Triple Protection 15W-40', 'Protección avanzada contra desgaste', 55.00, 1, 1, 'activo'),
('SH-ROT-T5', 'Shell Rotella T5 Synthetic Blend 15W-40', 'Mezcla sintética', 65.00, 1, 1, 'activo'),
('SH-ROT-T6', 'Shell Rotella T6 Full Synthetic 15W-40', '100% Sintético para trabajo extremo', 85.00, 1, 1, 'activo'),

-- SHELL RIMULA
('SH-RIM-R3', 'Shell Rimula R3 Turbo 15W-40', 'Protección turboalimentada', 50.00, 1, 1, 'activo'),
('SH-RIM-R4X', 'Shell Rimula R4 X 15W-40', 'Protección Energised Protection', 58.00, 1, 1, 'activo'),
('SH-RIM-R5', 'Shell Rimula R5 LE 15W-40', 'Bajas emisiones, ahorro de energía', 70.00, 1, 1, 'activo'),

-- CASTROL VECTON
('CA-VEC-CJ4', 'Castrol Vecton 15W-40 CJ-4', 'Maximiza rendimiento del motor', 60.00, 1, 2, 'activo'),
('CA-VEC-CK4', 'Castrol Vecton 15W-40 CK-4', 'Para motores modernos Euro V/VI', 62.00, 1, 2, 'activo'),
('CA-VEC-LD', 'Castrol Vecton Long Drain 15W-40', 'Intervalos de cambio extendidos', 75.00, 1, 2, 'activo'),

-- CASTROL CRB & GTX
('CA-CRB-MUL', 'Castrol CRB Multi 15W-40', 'Versátil y duradero', 40.00, 1, 2, 'activo'),
('CA-CRB-TUR', 'Castrol CRB Turbo 15W-40', 'Para motores turbo', 42.00, 1, 2, 'activo'),
('CA-GTX-DSL', 'Castrol GTX Diesel 15W-40', 'Combate la acumulación de lodos', 45.00, 1, 2, 'activo'),

-- REFRIGERANTES (Coolants)
('SH-COOL-ELC', 'Shell Rotella ELC Red Coolant', 'Refrigerante de larga duración rojo', 35.00, 2, 1, 'activo'),
('SH-COOL-GLY', 'Shell Glycoshell Concentrate', 'Concentrado premium', 40.00, 2, 1, 'activo'),
('CA-RAD-SF', 'Castrol Radicool SF-O Premix', 'Listo para usar', 30.00, 2, 2, 'activo'),
('CA-RAD-NF', 'Castrol Radicool NF', 'Sin nitritos', 32.00, 2, 2, 'activo');