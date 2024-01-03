<?php

class Cliente {
    private $id;
    private $dni;
    private $nombre;
    private $direccion;
    private $localidad;
    private $provincia;
    private $telefono;
    private $email;
    private $contrasena; // Agregado campo contrasena
    private $rol;
    private $activo; // Agregado campo activo

    public function __construct($id, $dni, $nombre, $direccion, $localidad, $provincia, $telefono, $email, $contrasena, $rol, $activo) {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->localidad = $localidad;
        $this->provincia = $provincia;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
        $this->activo = $activo;
    }

    // Métodos de acceso

    public function getContrasena() {
        return $this->contrasena;
    }

    public function getActivo() {
        return $this->activo;
    }

    // Otros métodos de acceso

}

class ClienteRepository {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function buscarPorDni($dni) {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE dni = ?");
        $stmt->execute([$dni]);
        $row = $stmt->fetch();

        if ($row) {
            return new Cliente(
                $row['id'],
                $row['dni'],
                $row['nombre'],
                $row['direccion'],
                $row['localidad'],
                $row['provincia'],
                $row['telefono'],
                $row['email'],
                $row['contrasena'],
                $row['rol'],
                $row['activo']
            );
        }

        return null;
    }

    public function obtenerTodos() {
        $stmt = $this->pdo->query("SELECT * FROM usuarios");
        $clientes = [];

        while ($row = $stmt->fetch()) {
            $clientes[] = new Cliente(
                $row['id'],
                $row['dni'],
                $row['nombre'],
                $row['direccion'],
                $row['localidad'],
                $row['provincia'],
                $row['telefono'],
                $row['email'],
                $row['contrasena'],
                $row['rol'],
                $row['activo']
            );
        }

        return $clientes;
    }
}

?>
