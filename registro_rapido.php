<?php
require 'db_connection.php';
include 'header.php';
session_start();

$errors = array();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Nombre = $_POST['Nombre'];
    $Apellidos = $_POST['Apellidos'];
    $Direccion = $_POST['Direccion'];
    $Localidad = $_POST['Localidad'];
    $Provincia = $_POST['Provincia'];
    $Pais = $_POST['Pais'];
    $CodPos = $_POST['CodPos'];
    $Telefono = $_POST['Telefono'];
    $Email = $_POST['Email'];
    $Contrasena = password_hash($_POST['Contrasena'], PASSWORD_DEFAULT);
    $Rol = 'cliente'; 

    // Additional payment information
    $FormaPago = $_POST['FormaPago'];

    if (empty($Nombre) || empty($Apellidos) || empty($Direccion) || empty($Localidad) ||
        empty($Provincia) || empty($Pais) || empty($CodPos) || empty($Telefono) || empty($Email) || empty($Contrasena) || empty($FormaPago)) {
        $errors[] = "Por favor, complete todos los campos.";
    } else {
        // Additional validation and checks can be added here

        // Insert user into the database
        $stmt = $pdo->prepare("INSERT INTO usuarios (nombre, apellidos, direccion, localidad, provincia, pais, codpos, telefono, email, contrasena, rol) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$Nombre, $Apellidos, $Direccion, $Localidad, $Provincia, $Pais, $CodPos, $Telefono, $Email, $Contrasena, $Rol]);

        // Redirect to the payment page or any other relevant page
        header("Location:  realizar_compra.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registro de compra</title>
    <link rel="stylesheet" href="styles.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://kit.fontawesome.com/eb496ab1a0.js" crossorigin="anonymous"></script>
    <style>
        body {
    font-family: 'Arial', sans-serif;
    background-color: #f8f9fa;
    margin: 0;
}

.container {
    max-width: 300px;
    width: 100%;
    padding: 20px;
    background-color: #fff;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
}

h2 {
    font-size: 24px;
    margin-top: 50px;
    margin-bottom: 30px;
}

form {
    margin-top: 20px;
}

input,
select {
    margin-bottom: 15px;
    width: 100%;
    box-sizing: border-box;
    padding: 10px; /* Added padding for better visual appearance */
    border: 1px solid #ddd; /* Added border for input fields */
    border-radius: 4px;
}

input[type="submit"] {
    width: 100%;
    padding: 10px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    transition: background-color 0.3s;
    background-color: #007bff; /* Changed button color to a blue shade */
    color: #fff; /* Changed text color to white */
}

input[type="submit"]:hover {
    background-color: #0056b3; /* Darker shade on hover */
}

.error {
    color: #dc3545;
    margin-top: 10px;
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Datos de Envío y Registro</h2>
        <form method="POST">
            <input type="text" name="Nombre" placeholder="Nombre">
            <input type="text" name="Apellidos" placeholder="Apellidos">
            <input type="text" name="Direccion" placeholder="Dirección">
            <input type="text" name="Localidad" placeholder="Localidad">
            <input type="text" name="Provincia" placeholder="Provincia">
            <input type="text" name="Pais" placeholder="País">
            <input type="text" name="CodPos" placeholder="Código Postal">
            <input type="text" name="Telefono" placeholder="Teléfono">
            <input type="text" name="Email" placeholder="Email">
            <input type="password" name="Contrasena" placeholder="Contraseña">
            
            <select name="FormaPago">
                <option value="paypal">PayPal</option>
                <option value="credit_card">Tarjeta de Crédito</option>
                <option value="transferencia">Transferencia</option>
            </select>

            <input type="submit" value="Guardar" class="btn btn-success">
        </form>
        <?php if (!empty($errors)) {
            foreach ($errors as $error) {
                echo '<p class="error">' . $error . '</p>';
            }
        } ?>
    </div>
    <?php include 'footer.php'; ?>

</body>
</html>
