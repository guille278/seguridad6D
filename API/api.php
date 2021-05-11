<?php
//include_once 'recursos.php';
include_once 'models/Usuario.php';
include_once 'models/Alerta.php';
include_once 'models/Dispositivo.php';
include_once 'models/Ubicacion.php';
include_once 'models/Historia.php';
include_once 'models/Captura.php';
$usuario = new Usuario();
$alerta = new Alerta();
$dispositivo = new Dispositivo();
$historia = new Historia();
$ubicacion = new Ubicacion();
$captura = new Captura();
$auth = array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';
$psw = array_key_exists('PHP_AUTH_PW', $_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
if ($auth != 'admin' || $psw != '12345') {
    die(json_encode(['Error' => 'Error de autenticacion.']));
}
$error_metodo = json_encode(['Error' => 'Metodo HTTP no valido']);
$error_coleccion = json_encode(['Error' => 'No existe la coleccion']);
$error_id = json_encode(['Error' => 'ID no valido o no existe']);
$error_id_asignado = json_encode(['Error' => 'ID no asignado']);

$nuevo_JSON = file_get_contents('php://input');
$data = (array) json_decode($nuevo_JSON);


switch ($_SERVER['REQUEST_METHOD']) {
    case 'GET':
        switch ($_GET['collection']) {
            case 'alertas':
                echo !isset($_GET['id']) ? $alerta->verAlertas() : $alerta->buscaAlerta($_GET['id']);
                break;
            case 'dispositivos':
                echo !isset($_GET['id']) ? $dispositivo->verDispositivos() : $dispositivo->buscaDispositivo($_GET['id']);
                break;
            case 'historial':
                echo !isset($_GET['id']) ? $historia->verHistorial() : '';
                break;
            case 'ubicaciones':
                echo !isset($_GET['id']) ? $ubicacion->verUbicacion() : '';
                break;
            case 'usuarios':
                echo !isset($_GET['id']) ? $usuario->verUsuarios() : $usuario->buscaUsuario($_GET['id']);
                break;
            default:
                echo $error_coleccion;
                break;
        }
        break;
    case 'POST':
        switch ($_GET['collection']) {
            case 'alertas':
                $alerta->setTipo($data['tipo']);
                $alerta->setIdDispositivo($data['idDispositivo']);
                $historia->setIdAlerta($alerta->nuevaAlerta());
                $captura->setRuta($data['nombre_archivo']);
                $historia->setIdVideo($captura->nuevaCaptura());
                echo $historia->insertaHistoria();
                break;
            case 'dispositivos':
                break;
            case 'ubicaciones':
                break;
            case 'usuarios':
                $usuario->setEmail($data['email']);
                $usuario->setPassword($data['password']);
                $usuario->setTelefono($data['telefono']);
                $usuario->setNombre($data['nombre']);
                $usuario->setApellidoP($data['apellidoP']);
                $usuario->setApellidoM($data['apellidoM']);
                $usuario->setDireccion($data['direccion']);
                echo $usuario->nuevoUsuario();
                break;
            default:
                echo $error_coleccion;
                break;
        }
        break;
    case 'PUT':
        switch ($_GET['collection']) {
            case 'dispositivos':
                break;
            case 'ubicaciones':
                break;
            case 'usuarios':
                $usuario->setEmail($data['email']);
                $usuario->setPassword($data['password']);
                $usuario->setTelefono($data['telefono']);
                $usuario->setNombre($data['nombre']);
                $usuario->setApellidoP($data['apellidoP']);
                $usuario->setApellidoM($data['apellidoM']);
                $usuario->setDireccion($data['direccion']);
                $usuario->setEstado($data['estado']);
                echo isset($_GET['id']) ? $usuario->editaUsuario($_GET['id']) : $error_id_asignado;
                break;
            default:
                echo $error_coleccion;
                break;
        }
        break;
    case 'DELETE':
        switch ($_GET['collection']) {
            case 'dispositivos':
                break;
            case 'ubicaciones':
                break;
            case 'usuarios':
                echo isset($_GET['id']) ? $usuario->eliminaUsuario($_GET['id']) : $error_id_asignado;
                break;
            default:
                echo $error_coleccion;
                break;
        }
        break;
    default:
        echo $error_metodo;
        break;
}

