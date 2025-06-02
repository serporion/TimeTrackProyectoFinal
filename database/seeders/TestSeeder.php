<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TestSeeder extends Seeder
{
    public function run(): void
    {
        // Usuarios
        DB::table('usuarios')->insert([
            ['id' => 1, 'name' => 'Empleado Prueba 1', 'dni' => '000000001', 'email' => 'empleado1@test.com', 'password' => bcrypt('empleado1'), 'role' => 'empleado', 'consiente_datos' => 1],
            ['id' => 2, 'name' => 'Empleado Prueba 2', 'dni' => '000000002', 'email' => 'empleado2@test.com', 'password' => bcrypt('empleado2'), 'role' => 'empleado', 'consiente_datos' => 1],
            ['id' => 3, 'name' => 'Empleado Prueba 3', 'dni' => '000000003', 'email' => 'empleado3@test.com', 'password' => bcrypt('empleado3'), 'role' => 'empleado', 'consiente_datos' => 1],
            ['id' => 4, 'name' => 'Admin Terminal', 'dni' => '000000004', 'email' => 'admin_terminal@test.com', 'password' => bcrypt('terminal'), 'role' => 'administrador', 'consiente_datos' => 1],
            ['id' => 5, 'name' => 'Admin Completo', 'dni' => '000000005', 'email' => 'admin_completo@test.com', 'password' => bcrypt('completo'), 'role' => 'administrador', 'consiente_datos' => 1],
        ]);

        // Credenciales
        /* La aplicación genera las credenciales de forma automática.
        DB::table('credenciales')->insert([
            ['usuario_id' => 1, 'clave' => 'claveempleado1'],
            ['usuario_id' => 2, 'clave' => 'claveempleado2'],
            ['usuario_id' => 3, 'clave' => 'claveempleado3'],
            ['usuario_id' => 4, 'clave' => 'claveadmin_terminal'],
            ['usuario_id' => 5, 'clave' => 'claveadmin_completo'],
        ]);
        */

        // Contratos
        DB::table('contratos')->insert([
            ['usuario_id' => 1, 'horas' => 20, 'fecha_inicio' => '2024-12-03 18:30:37', 'fecha_fin' => '2025-05-02 18:30:37'],
            ['usuario_id' => 1, 'horas' => 40, 'fecha_inicio' => Carbon::now()],
            ['usuario_id' => 2, 'horas' => 20, 'fecha_inicio' => Carbon::now()],
            ['usuario_id' => 3, 'horas' => 20, 'fecha_inicio' => Carbon::now()],
        ]);

        // Permisos
        DB::table('permisos')->insert([
            ['usuario_id' => 4, 'permiso' => 'gestionar_inicio'],
            ['usuario_id' => 5, 'permiso' => 'gestionar_usuarios'],
            ['usuario_id' => 5, 'permiso' => 'gestionar_fichajes'],
            ['usuario_id' => 5, 'permiso' => 'gestionar_permisos'],
            ['usuario_id' => 5, 'permiso' => 'gestionar_inicio'],
        ]);

        // QRs
        DB::table('qrs')->insert([
            ['id' => 1, 'contenido' => json_encode(['usuario_id' => 1, 'tipo' => 'entrada', 'qr_id' => 1]), 'estado' => 'valido', 'timestamp' => Carbon::now()],
            ['id' => 2, 'contenido' => json_encode(['usuario_id' => 2, 'tipo' => 'entrada', 'qr_id' => 2]), 'estado' => 'valido', 'timestamp' => Carbon::now()],
            ['id' => 3, 'contenido' => json_encode(['usuario_id' => 3, 'tipo' => 'entrada', 'qr_id' => 3]), 'estado' => 'valido', 'timestamp' => Carbon::now()],
        ]);

        // Fotos
        DB::table('fotos')->insert([
            ['id' => 1, 'ruta_imagen' => 'fotos/captura1.jpg', 'timestamp' => '2025-06-01 10:52:48'],
            ['id' => 2, 'ruta_imagen' => 'fotos/captura2.jpg', 'timestamp' => '2025-05-31 10:52:48'],
            ['id' => 3, 'ruta_imagen' => 'fotos/captura3.jpg', 'timestamp' => '2025-05-30 10:52:48'],
        ]);

        // Fichajes
        DB::table('fichajes')->insert([
            ['usuario_id' => 1, 'qr_id' => 1, 'foto_id' => 1, 'tipo' => 'entrada', 'timestamp' => '2025-06-01 10:52:48'],
            ['usuario_id' => 2, 'qr_id' => 2, 'foto_id' => 2, 'tipo' => 'entrada', 'timestamp' => '2025-05-31 10:52:48'],
            ['usuario_id' => 3, 'qr_id' => 3, 'foto_id' => 3, 'tipo' => 'entrada', 'timestamp' => '2025-05-30 10:52:48'],
        ]);

        // Auditorías
        DB::table('auditorias')->insert([
            ['usuario_id' => 5, 'fichaje_id' => 1, 'verificado' => 1, 'observaciones' => 'Todo correcto'],
            ['usuario_id' => 5, 'fichaje_id' => 2, 'verificado' => 1, 'observaciones' => 'Validado correctamente'],
            ['usuario_id' => 5, 'fichaje_id' => 3, 'verificado' => 1, 'observaciones' => 'Sin incidencias'],
        ]);
    }
}
