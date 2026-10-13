<?php

namespace Database\Seeders;

use App\Models\Vocero;
use App\Models\Usuario;
use App\Models\Aprendiz;
use App\Models\Ficha;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class VocerosSeeder extends Seeder
{
    public function run(): void
    {
        // Ficha 1 - 2 Voceros
        $ficha1 = Ficha::find(1);
        $aprendices_ficha1 = Aprendiz::where('ficha_id', $ficha1->id)->take(2)->get();

        foreach ($aprendices_ficha1 as $index => $aprendiz) {
            $usuario = Usuario::create([
                'correo' => 'vocero' . ($index + 1) . '@ficha2605741.sena.edu.co',
                'nombres' => $aprendiz->nombres . ' ' . $aprendiz->apellidos,
                'apellidos' => $aprendiz->apellidos,
                'documento' => $aprendiz->documento,
                'celular' => $aprendiz->telefono,
                'password' => Hash::make('Vocero123!'),
                'rol_id' => 2, // Rol Vocero
                'activo' => true,
                'primer_acceso' => true,
            ]);

            Vocero::create([
                'aprendiz_id' => $aprendiz->id,
                'usuario_id' => $usuario->id,
                'ficha_id' => $ficha1->id,
                'activo' => true,
            ]);
        }

        // Ficha 2 - 2 Voceros
        $ficha2 = Ficha::find(2);
        $aprendices_ficha2 = Aprendiz::where('ficha_id', $ficha2->id)->take(2)->get();

        foreach ($aprendices_ficha2 as $index => $aprendiz) {
            $usuario = Usuario::create([
                'correo' => 'vocero' . ($index + 3) . '@ficha2605742.sena.edu.co',
                'nombres' => $aprendiz->nombres . ' ' . $aprendiz->apellidos,
                'apellidos' => $aprendiz->apellidos,
                'documento' => $aprendiz->documento,
                'celular' => $aprendiz->telefono,
                'password' => Hash::make('Vocero123!'),
                'rol_id' => 2, // Rol Vocero
                'activo' => true,
                'primer_acceso' => true,
            ]);

            Vocero::create([
                'aprendiz_id' => $aprendiz->id,
                'usuario_id' => $usuario->id,
                'ficha_id' => $ficha2->id,
                'activo' => true,
            ]);
        }

        // Ficha 3 - 2 Voceros
        $ficha3 = Ficha::find(3);
        $aprendices_ficha3 = Aprendiz::where('ficha_id', $ficha3->id)->take(2)->get();

        foreach ($aprendices_ficha3 as $index => $aprendiz) {
            $usuario = Usuario::create([
                'correo' => 'vocero' . ($index + 5) . '@ficha2605743.sena.edu.co',
                'nombres' => $aprendiz->nombres . ' ' . $aprendiz->apellidos,
                'apellidos' => $aprendiz->apellidos,
                'documento' => $aprendiz->documento,
                'celular' => $aprendiz->telefono,
                'password' => Hash::make('Vocero123!'),
                'rol_id' => 2, // Rol Vocero
                'activo' => true,
                'primer_acceso' => true,
            ]);

            Vocero::create([
                'aprendiz_id' => $aprendiz->id,
                'usuario_id' => $usuario->id,
                'ficha_id' => $ficha3->id,
                'activo' => true,
            ]);
        }
    }
}
