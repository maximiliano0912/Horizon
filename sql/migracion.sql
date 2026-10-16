-- HORIZON - migración sobre la base "horizon" (ejecutar en phpMyAdmin > SQL)

-- 1) Los productos pueden estar en ARS, USD o EUR (el catálogo ya mezcla monedas)
ALTER TABLE productos
  ADD COLUMN moneda ENUM('ARS','USD','EUR') NOT NULL DEFAULT 'ARS' AFTER precio_unitario;

-- 2) Cargar los 12 paquetes del catálogo para que el carrito los reconozca
INSERT INTO productos (cod_producto, descripcion, precio_unitario, moneda) VALUES
('HZN-001','Bariloche - Río Negro, Argentina (7 días / 6 noches)',680000,'ARS'),
('HZN-002','Mendoza - Mendoza, Argentina',520000,'ARS'),
('HZN-003','Cataratas del Iguazú - Misiones, Argentina',410000,'ARS'),
('HZN-004','Ushuaia - Tierra del Fuego, Argentina',720000,'ARS'),
('HZN-005','Salta y Jujuy - Salta, Argentina',590000,'ARS'),
('HZN-006','Mar del Plata - Buenos Aires, Argentina',390000,'ARS'),
('HZN-007','Río de Janeiro - Brasil',1190,'USD'),
('HZN-008','Madrid - España',1490,'EUR'),
('HZN-009','Cancún - Quintana Roo, México',1099,'USD'),
('HZN-010','París - Francia',1390,'EUR'),
('HZN-011','Nueva York - Estados Unidos',1249,'USD'),
('HZN-012','Roma - Italia',1320,'EUR')
ON DUPLICATE KEY UPDATE descripcion=VALUES(descripcion), precio_unitario=VALUES(precio_unitario), moneda=VALUES(moneda);
