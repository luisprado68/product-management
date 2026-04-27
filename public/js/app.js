const API_URL = app_url + '/api/productos';

const listaProductos = document.getElementById('lista-productos');
const mensaje = document.getElementById('mensaje');

// Función para mostrar mensajes al usuario
function mostrarMensaje(texto, tipo) {
    mensaje.textContent = texto;
    mensaje.className = tipo;
    setTimeout(() => mensaje.className = 'hidden', 3000);
}

// Cargar productos al iniciar
async function obtenerProductos() {
    try {
        const response = await fetch(API_URL);
        if (!response.ok) throw new Error('Error al obtener productos');
        const productos = await response.json();

        listaProductos.innerHTML = '';
        productos.forEach(p => {
            const row = `<tr>
                <td>${p.nombre}</td>
                <td>${p.descripcion}</td>
                <td>$${p.precio}</td>
                <td>$${p.precio_usd}</td>
                <td><button class="btn-warning" onclick="editarProducto(${p.id}, '${p.nombre}','${p.descripcion}', ${p.precio})">Editar</button>
                <button class="btn-danger" onclick="eliminarProducto(${p.id})">Eliminar</button></td>
                
                
            </tr>`;
            listaProductos.innerHTML += row;
        });
    } catch (e) {
        mostrarMensaje(e.message, 'error');
    }
}

// Agregar producto
// Variable global para capturar los inputs
const idInput = document.getElementById('producto-id');
const nombreInput = document.getElementById('name');
const descripcionInput = document.getElementById('description');
const precioInput = document.getElementById('price');
const btnSubmit = document.getElementById('btn-submit');
const btnCancelar = document.getElementById('btn-cancelar');

// Lógica de Submit (Create vs Update)
document.getElementById('form-producto').onsubmit = async (e) => {
    e.preventDefault();
    const id = idInput.value;
    const data = { nombre: nombreInput.value, descripcion: descripcionInput.value, precio: precioInput.value };
    console.debug(data)

    // Si hay ID, es PUT (Editar), si no, es POST (Crear)
    const method = id ? 'PUT' : 'POST';
    const url = id ? `${API_URL}/${id}` : API_URL;

    console.debug(url)

    try {
        const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });
        const result = await response.json(); // Leemos el JSON siempre
        if (!response.ok) throw new Error(result.message || 'Error desconocido en el servidor');


        mostrarMensaje(result.message || 'Operación exitosa', 'success');
        resetForm();
        obtenerProductos();
    } catch (e) {
        mostrarMensaje(e.message, 'error');
    }
};

// Función para "Cargar" el formulario (Editar)
function editarProducto(id, nombre,descripcion, precio) {
    idInput.value = id;
    nombreInput.value = nombre;
    descripcionInput.value = descripcion;
    precioInput.value = precio;

    btnSubmit.textContent = 'Actualizar Producto';
    btnCancelar.classList.remove('hidden'); // Mostramos botón cancelar
}

// Resetear el formulario
function resetForm() {
    idInput.value = '';
    nombreInput.value = '';
    descripcionInput.value = '';
    precioInput.value = '';
    btnSubmit.textContent = 'Agregar Producto';
    btnCancelar.classList.add('hidden');
}

// Eliminar Producto
async function eliminarProducto(id) {
    if (!confirm('¿Estás seguro de eliminar este producto?')) return;

    try {
        const response = await fetch(`${API_URL}/${id}`, { method: 'DELETE' });
        if (!response.ok) throw new Error('Error al eliminar');
        obtenerProductos();
        mostrarMensaje('Producto eliminado', 'success');
    } catch (e) {
        mostrarMensaje(e.message, 'error');
    }
}

obtenerProductos();