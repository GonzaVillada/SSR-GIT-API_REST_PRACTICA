
// Node.js + EJS SSR server

// Cargar módulos con CommonJS
const express = require("express");
const fetch = (...args) => import('node-fetch').then(({default: fetch}) => fetch(...args)); // Node <18
const path = require("path");

// Crear app
const app = express();
const PORT = 3000;

// Configurar motor de plantillas y vistas
app.set("views", path.join(__dirname, "views"));
app.set("view engine", "ejs");

// Carpeta pública para CSS, JS y assets
app.use(express.static(path.join(__dirname, "public")));

// Ruta principal - renderizado SSR
app.get("/", async (req, res) => {
  try {
    // Llamada a la API REST PHP
    const response = await fetch("http://localhost:8000/tareas");
    const tareas = await response.json();


    // Renderizar la página con la lista de tareas
    res.render("index", { tareas });
  } catch (error) {
    console.error("Error al obtener tareas desde Node:", error);
    res.render("index", { tareas: [] }); // render vacío si falla la API
  }
});

// Iniciar servidor
app.listen(PORT, () => {
  console.log(`Servidor Node.js escuchando en http://localhost:${PORT}`);
});
