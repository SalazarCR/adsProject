DROP DATABASE IF EXISTS inventario_db;
CREATE DATABASE inventario_db CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE inventario_db;

-- ==========================================
-- 1. TABLA CATEGORIAS
-- ==========================================
CREATE TABLE categorias (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(50) NOT NULL UNIQUE,
    descripcion TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
) ENGINE=InnoDB;

-- ==========================================
-- 2. TABLA PROVEEDORES
-- ==========================================
CREATE TABLE proveedores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    contacto VARCHAR(100),
    telefono VARCHAR(20),
    direccion TEXT,
    estado ENUM('activo', 'inactivo') DEFAULT 'activo'
) ENGINE=InnoDB;

-- ==========================================
-- 3. TABLA USUARIOS (MODIFICADA)
-- ==========================================
-- Se agregó 'telefono' y se confirmó 'email'.
CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    rol ENUM('admin','auxiliar','analista') NOT NULL DEFAULT 'auxiliar',
    email VARCHAR(100),          -- Ya existía, se mantiene
    telefono VARCHAR(20),        -- [NUEVO] Campo solicitado
    estado_tmp ENUM('activo', 'inactivo') DEFAULT 'activo',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ==========================================
-- 4. TABLA PRODUCTOS
-- ==========================================
CREATE TABLE productos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    codigo_interno VARCHAR(50) UNIQUE,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio_venta DECIMAL(10,2),
    categoria_id INT,
    proveedor_id INT,
    imagen VARCHAR(100) DEFAULT 'default.png',
    estado ENUM('activo', 'inactivo') DEFAULT 'activo', 
    FOREIGN KEY (categoria_id) REFERENCES categorias(id) ON DELETE SET NULL,
    FOREIGN KEY (proveedor_id) REFERENCES proveedores(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ==========================================
-- 5. TABLA LOTES
-- ==========================================
CREATE TABLE lotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    producto_id INT NOT NULL,
    codigo_lote VARCHAR(50) NOT NULL,
    fecha_vencimiento DATE,
    fecha_ingreso DATE DEFAULT (CURRENT_DATE),
    stock_actual INT NOT NULL DEFAULT 0,
    costo_unitario DECIMAL(10,2),
    estado ENUM('activo', 'agotado', 'vencido') DEFAULT 'activo',
    FOREIGN KEY (producto_id) REFERENCES productos(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ==========================================
-- 6. TABLA MOVIMIENTOS
-- ==========================================
CREATE TABLE movimientos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    lote_id INT NOT NULL,  
    tipo ENUM('entrada','salida','ajuste') NOT NULL,
    cantidad INT NOT NULL,
    motivo VARCHAR(255),
    fecha DATETIME DEFAULT CURRENT_TIMESTAMP,
    usuario_id INT,
    FOREIGN KEY (lote_id) REFERENCES lotes(id) ON DELETE CASCADE,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE SET NULL
) ENGINE=InnoDB;
-- ==========================================
-- 7. TABLA CODIGO VERIFICACION
-- ==========================================
CREATE TABLE codigos_verificacion (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario_id INT NOT NULL,
    code VARCHAR(10) NOT NULL,
    expires_at DATETIME NOT NULL,
    is_used TINYINT(1) DEFAULT 0,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (usuario_id) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

