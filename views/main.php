<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Gestión de Productos</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
<div class="title">
    <h1>Catálogo de Productos</h1>
</div>


<div id="mensaje" class="hidden"></div>

<form id="form-producto">
    <input type="hidden" id="producto-id">
    <div>
        <input type="text" id="name" placeholder="Nombre" required>
        <input type="text" id="description" placeholder="Descripción" >
        <input type="number" id="price" step="0.01" placeholder="0.00" required>
    </div>


    <button type="submit" id="btn-submit">Agregar Producto</button>
    <button type="button" id="btn-cancelar" class="hidden" onclick="resetForm()">Cancelar</button>
</form>

<table style="padding: 20px">
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Descripción</th>
        <th>Precio</th>
        <th>Precio USD</th>
        <th>Acciones</th>
    </tr>
    </thead>
    <tbody id="lista-productos"></tbody>
</table>
<script>
    // Inyectas la variable de forma segura en una constante global
    const app_url = <?php echo json_encode(getenv('APP_URL') ?: 'http://localhost:8000'); ?>;
</script>
<script src="js/app.js"></script>
</body>
</html>