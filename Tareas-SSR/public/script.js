document.getElementById("formTarea").addEventListener("submit", async (e) => {
  e.preventDefault();

  const title = document.getElementById("nuevaTarea").value.trim();
  if (!title) return;

  try {
    await fetch("http://localhost:8000/tareas", {
      method: "POST",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ title })
    });

    // Limpiar input
    document.getElementById("nuevaTarea").value = "";

    // Recargar la página para ver la nueva tarea (SSR)
    location.reload();
  } catch (error) {
    console.error("Error al agregar la tarea:", error);
    alert("No se pudo agregar la tarea");
  }
});

// Función para eliminar tarea
async function eliminarTarea(id) {
  if (!confirm("¿Seguro que querés eliminar esta tarea?")) return;

  try {
    const response = await fetch(`http://localhost:8000/tareas/${id}`, {
      method: "DELETE"
    });

    if (!response.ok) {
      throw new Error("Error al eliminar la tarea");
    }

    // Recargar para actualizar la lista
    location.reload();
  } catch (error) {
    console.error("Error:", error);
    alert("No se pudo eliminar la tarea");
  }
}

async function toggleCompletada(id, checkbox) {
  const nuevoEstado = checkbox.checked ? 1 : 0;

  try {
    const response = await fetch(`http://localhost:8000/tareas/${id}`, {
      method: "PATCH",
      headers: { "Content-Type": "application/json" },
      body: JSON.stringify({ completada: nuevoEstado })
    });

    if (!response.ok) throw new Error("Error al actualizar la tarea");

    // Mover la tarea al contenedor correcto
    const li = document.getElementById(`tarea-${id}`);
    const listaDestino = nuevoEstado ? document.getElementById("completadas") : document.getElementById("pendientes");
    listaDestino.prepend(li); // así va al inicio

  } catch (error) {
    console.error(error);
    alert("No se pudo actualizar la tarea");
    // Revertir el checkbox si falla
    checkbox.checked = !checkbox.checked;
  }
}
