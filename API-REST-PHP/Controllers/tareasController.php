<?php

use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\ServerRequest;

require_once __DIR__ . '/../models/Tareas.php';

class tareasController
{
    // GET /tareas
    public function obtenerTareas()
    {
        $tareas = new Tareas();
        return new JsonResponse($tareas->getAll());
    }

    // POST /tareas
    public function cargarTarea(ServerRequest $request)
    {
        $data = $request->getParsedBody();

        // Soporte por si vienen los datos raw JSON
        if (empty($data)) {
            $json = $request->getBody()->getContents();
            $data = json_decode($json, true) ?? [];
        }

        $title = $data['title'] ?? '';

        if (!preg_match('/^.+$/s', $title)) {
            return new JsonResponse(['message' => 'Título inválido'], 400);
        }

        $tareas = new Tareas();
        $resultado = $tareas->create($title);

        return new JsonResponse($resultado);
    }

    public function eliminarTarea($id)
    {
        if (!preg_match('/^[1-9]\d*$/', $id)) {
            return new JsonResponse(['message' => 'ID inválido'], 400);
        }

        $tareas = new Tareas;
        $resultado = $tareas->delete($id);

        if ($resultado) {
            return new JsonResponse(['message' => 'tarea eliminada']);
        } else {
            return new JsonResponse(['message' => 'Error al eliminar la tarea'], 500);
        }
    }

    public function actualizarEstadoTarea($id)
    {
        // Validar ID
        if (!preg_match('/^[1-9]\d*$/', $id)) {
            return new JsonResponse(['message' => 'ID inválido'], 400);
        }

        // Leer el body JSON
        $data = json_decode(file_get_contents('php://input'), true);

        if (!isset($data['completada']) || !in_array($data['completada'], [0, 1])) {
            return new JsonResponse(['message' => 'Estado inválido'], 400);
        }

        $nuevoEstado = $data['completada'];

        // Instanciar el modelo
        $tareas = new Tareas;
        $resultado = $tareas->updateEstado($id, $nuevoEstado);

        if ($resultado) {
            return new JsonResponse(['message' => 'Estado actualizado']);
        } else {
            return new JsonResponse(['message' => 'Error al actualizar la tarea'], 500);
        }
    }
}
