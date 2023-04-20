<?php
namespace App\Http\Controllers;

class Utilitat{
   public static function errorMessage($e) {
        if (!empty($e->errorInfo[1])) {
            switch($e->errorInfo[1]) {
                case '1062':
                    $mensaje = "Registre duplicat, ja existeix un usuari amb aquest nom d'usuari";
                    break;
                case '1451':
                    $mensaje = 'Registre amb elements relacionats';
                    break;
                default:
                    $mensaje = $e->errorInfo[1] . ' - ' . $e->errorInfo[2];
                    break;
            }
        } else {
            switch ($e->getCode()) {
                case 1044:
                    $mensaje = "Usuari i/o contrasenya incorrecte";
                    break;
                case 1049:
                    $mensaje = "Base de dades desconeguda";
                    break;
                case 2002:
                    $mensaje = "No es troba el servidor";
                    break;
                default:
                    $mensaje = $e->getCode() . ' - ' . $e->getMessage();
                    break;
            }
        }
        return $mensaje;
    }
}