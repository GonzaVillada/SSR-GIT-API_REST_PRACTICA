<?php
require_once __DIR__ . "/../settings/db.php";

class Tareas 
{
    protected $connection;

    public function __construct()
    {
        $this->connection = db::connect();
    }

    // Obtener todas las tareas
    public function getAll()
    {
        try {
            $query = "SELECT id, nombre, completada FROM tareas ORDER BY id DESC";
            $stmt = $this->connection->prepare($query);
            $stmt->execute();

            if ($stmt->errno) {
                throw new Exception($stmt->error);
            }

            $resultado = $stmt->get_result();
            $data_array = [];
            while ($data = $resultado->fetch_assoc()) {
                $data_array[] = $data;
            }
            return $data_array;

        } catch (Exception $e) {
            error_log("Error al obtener tareas: " . $e->getMessage());
            return [];
        }
    }

    // Crear una nueva tarea
    public function create($title)
    {
        try {
            $query = "INSERT INTO tareas (nombre, completada) VALUES (?, 0)";
            $stmt = $this->connection->prepare($query);

            if (!$stmt) {
                throw new Exception("Error al preparar la consulta: " . $this->connection->error);
            }

            $stmt->bind_param("s", $title);
            $stmt->execute();

            if ($stmt->errno) {
                throw new Exception($stmt->error);
            }

            return ['message' => 'Tarea creada correctamente'];

        } catch (Exception $e) {
            error_log("Error al crear tarea: " . $e->getMessage());
            return ['message' => 'Error al crear tarea'];
        }
    }

    public function delete($id)
{
    try {
        $query = 'DELETE FROM tareas WHERE id = ?';

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->connection->error);
        }

        $stmt->bind_param("i", $id);
        $stmt->execute();

        if ($stmt->errno) {
            throw new Exception($stmt->error);
        }

        return ['message' => 'tarea eliminada'];

    } catch (Exception $e) {
        error_log("Error en delete(): " . $e->getMessage());
        return false;
    }
}

public function updateEstado($id, $nuevoEstado)
{
    try {
        $query = 'UPDATE tareas SET completada = ? WHERE id = ?';

        $stmt = $this->connection->prepare($query);

        if (!$stmt) {
            throw new Exception("Error al preparar la consulta: " . $this->connection->error);
        }

        // "i" para enteros, dos veces: completada y id
        $stmt->bind_param("ii", $nuevoEstado, $id);
        $stmt->execute();

        if ($stmt->errno) {
            throw new Exception($stmt->error);
        }

        return ['message' => 'Estado actualizado'];

    } catch (Exception $e) {
        error_log("Error en updateEstado(): " . $e->getMessage());
        return false;
    }
}



}
