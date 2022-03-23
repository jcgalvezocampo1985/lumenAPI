<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Libro;
use Carbon\Carbon;

class LibroController extends Controller
{
    public function getLibros()
    {
        $datos = Libro::all();

        return response()->json($datos);
    }

    public function saveLibro(Request $request)
    {
        $datos = new Libro;
        $imagen = $request->file('imagen');
        $datos->titulo = $request->titulo;

        if($request->hasFile('imagen'))
        {
            $nombre_archivo_original = $imagen->getClientOriginalName();
            $nuevo_nombre_imagen = Carbon::now()->timestamp."_".$nombre_archivo_original;
            $carpeta_destino = './upload/';

            $imagen->move($carpeta_destino, $nuevo_nombre_imagen);

            $datos->imagen = ltrim($carpeta_destino,'.').$nuevo_nombre_imagen;
        }
        $datos->save();

        return response()->json("Registro agregado");
    }

    public function getLibro($id)
    {
        $datos = Libro::find($id);

        return response()->json($datos);
    }

    public function deleteLibro($id)
    {
        $datos = Libro::find($id);

        if($datos)
        {
            $ruta_archivo = base_path('public').$datos->imagen;

            if(file_exists($ruta_archivo))
            {
                unlink($ruta_archivo);
            }

            $datos->delete($id);
        }

        return response()->json("Registro eliminado");
    }

    public function updateLibro(Request $request, $id)
    {
        $datos = Libro::find($id);

        $datos->titulo = $request->titulo;
        $imagen = $request->file('imagen');

        if($request->hasFile('imagen'))
        {
            $ruta_archivo = base_path('public').$datos->imagen;

            if(is_file($ruta_archivo))
            {
                unlink($ruta_archivo);
            }

            $nombre_archivo_original = $imagen->getClientOriginalName();
            $nuevo_nombre_imagen = Carbon::now()->timestamp."_".$nombre_archivo_original;
            $carpeta_destino = './upload/';

            $imagen->move($carpeta_destino, $nuevo_nombre_imagen);

            $datos->imagen = ltrim($carpeta_destino,'.').$nuevo_nombre_imagen;
        }
        $datos->save();

        return response()->json($datos);
    }
}
