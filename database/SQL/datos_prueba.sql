
-- Usuarios
INSERT INTO usuarios (id, name, dni, email, password, role, consiente_datos) VALUES
(1, 'Empleado Prueba 1', '000000001', 'empleado1@test.com', 'hashedpassword', 'empleado', 1),
(2, 'Empleado Prueba 2', '000000002', 'empleado2@test.com', 'hashedpassword', 'empleado', 1),
(3, 'Empleado Prueba 3', '000000003', 'empleado3@test.com', 'hashedpassword', 'empleado', 1),
(4, 'Admin Terminal', '000000004', 'admin_terminal@test.com', 'hashedpassword', 'administrador', 1),
(5, 'Admin Completo', '000000005', 'admin_completo@test.com', 'hashedpassword', 'administrador', 1);

-- Credenciales
INSERT INTO credenciales (usuario_id, clave) VALUES
(1, 'claveempleado1'),
(2, 'claveempleado2'),
(3, 'claveempleado3'),
(4, 'claveadmin_terminal'),
(5, 'claveadmin_completo');

-- Contratos
INSERT INTO contratos (usuario_id, horas, fecha_inicio) VALUES
(1, 20, NOW()),
(1, 40, NOW()),
(2, 20, NOW()),
(3, 20, NOW());

-- Permisos administradores
INSERT INTO permisos (usuario_id, permiso) VALUES
(4, 'gestionar_inicio'),
(5, 'gestionar_usuarios'),
(5, 'gestionar_fichajes'),
(5, 'gestionar_permisos'),
(5, 'gestionar_inicio');

-- QRs
INSERT INTO qrs (id, contenido, estado, timestamp) VALUES
(1, '{"usuario_id":1,"tipo":"entrada","qr_id":1}', 'valido', NOW()),
(2, '{"usuario_id":2,"tipo":"entrada","qr_id":2}', 'valido', NOW()),
(3, '{"usuario_id":3,"tipo":"entrada","qr_id":3}', 'valido', NOW());

-- Fotos
INSERT INTO fotos (id, ruta_imagen, timestamp) VALUES
(1, 'fotos/captura1.jpg', '2025-06-01 10:52:32'),
(2, 'fotos/captura2.jpg', '2025-05-31 10:52:32'),
(3, 'fotos/captura3.jpg', '2025-05-30 10:52:32');

-- Fichajes
INSERT INTO fichajes (usuario_id, qr_id, foto_id, tipo, timestamp) VALUES
(1, 1, 1, 'entrada', '2025-06-01 10:52:32'),
(2, 2, 2, 'entrada', '2025-05-31 10:52:32'),
(3, 3, 3, 'entrada', '2025-05-30 10:52:32');

-- Auditorías
INSERT INTO auditorias (usuario_id, fichaje_id, verificado, observaciones) VALUES
(5, 1, 1, 'Todo correcto'),
(5, 2, 1, 'Validado correctamente'),
(5, 3, 1, 'Sin incidencias');
