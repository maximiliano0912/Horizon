-- HORIZON - migración 2 (ejecutar en phpMyAdmin > SQL, DESPUÉS de migracion.sql)
-- Guarda las opciones que el cliente elige en el carrito: método de viaje, fecha de salida y extras.
-- La cantidad (cantidad) pasa a ser la cantidad de personas que viajan.

ALTER TABLE detalle_pedido
  ADD COLUMN metodo_viaje VARCHAR(20)  NULL AFTER precio,
  ADD COLUMN fecha_viaje  DATE         NULL AFTER metodo_viaje,
  ADD COLUMN extras       VARCHAR(255) NULL AFTER fecha_viaje;
