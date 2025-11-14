<?php
require_once 'vendor/autoload.php';
require_once 'controllers/tareasController.php';

use MiladRahimi\PhpRouter\Router;
use Laminas\Diactoros\Response\JsonResponse;

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}


$router = Router::create();

$router->get('/', function () {
    return new JsonResponse(['message' => 'ok']);
});

$router->get('/tareas',[tareasController::class,'obtenerTareas']);


$router->post('/tareas',[tareasController::class,'cargarTarea']);


$router->delete('/tareas/{id}',[tareasController::class,'eliminarTarea']);


$router->patch('/tareas/{id}',[tareasController::class,'actualizarEstadoTarea']);



$router->dispatch();