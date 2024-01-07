<?php
require 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $codigo = $_POST['Codigo'];
    $nombre = $_POST['Nombre'];
    $descripcion = $_POST['Descripcion'];
    $nombreCategoria = $_POST['CategoriaID'];
    $precio = $_POST['Precio'];
    $enOferta = isset($_POST['enOferta']) ? 1 : 0;
    $activo = isset($_POST['Activo']) ? 1 : 0;

    if (!preg_match('/^[a-zA-Z]{3}[0-9]{1,5}$/', $codigo)) {
        die("Error: El código no es válido. Debe tener tres letras y hasta 5 números.");
    }
    
    // Validar imagen
    $imagen = $_FILES["Imagen"];
    $imagenSize = $imagen["size"];
    $imagenType = $imagen["type"];
    $imagenName = $imagen["name"];

    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    $maxFileSize = 300000;
    $maxWidth = 200;
    $maxHeight = 200;

    $pathToSave = "img/" . $imagenName;

    $imageInfo = getimagesize($imagen["tmp_name"]);
    $imageWidth = $imageInfo[0];
    $imageHeight = $imageInfo[1];

    if (!in_array(pathinfo($imagenName, PATHINFO_EXTENSION), $allowedExtensions) ||
        $imagenSize > $maxFileSize ||
        $imageWidth > $maxWidth ||
        $imageHeight > $maxHeight) {
        echo "<script>alert('Error: La imagen no cumple con los requisitos.');</script>";
        die();
    }

    move_uploaded_file($imagen["tmp_name"], $pathToSave);

    $stmt = $pdo->prepare("INSERT INTO Articulos (Codigo, Nombre, Descripcion, CategoriaID, Precio, Imagen, enOferta, Activo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$codigo, $nombre, $descripcion, $nombreCategoria, $precio, $pathToSave, $enOferta, $activo]);

    header("Location: informe_articulos.php?mensaje=Datos actualizados correctamente");
} else {
    echo "Acceso no autorizado.";
}
?>
