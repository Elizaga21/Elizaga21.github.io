<?php

class Cliente {
    private $id;
    private $dni;
    private $nombre;
    private $apellidos;
    private $direccion;
    private $localidad;
    private $provincia;
    private $pais; 
    private $codpos; 
    private $telefono;
    private $email;
    private $contrasena; 
    private $rol;
    private $activo;

    public function __construct($id, $dni, $nombre, $apellidos, $direccion, $localidad, $provincia, $pais, $codpos, $telefono, $email, $contrasena, $rol, $activo) {
        $this->id = $id;
        $this->dni = $dni;
        $this->nombre = $nombre;
        $this->apellidos = $apellidos;
        $this->direccion = $direccion;
        $this->localidad = $localidad;
        $this->provincia = $provincia;
        $this->pais = $pais;
        $this->codpos = $codpos;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->contrasena = $contrasena;
        $this->rol = $rol;
        $this->activo = $activo;
    }

    // Métodos de acceso

    public function getId() {
        return $this->id;
    }

    public function getDni() {
        return $this->dni;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getApellidos() {
        return $this->apellidos;
    }

    public function getDireccion() {
        return $this->direccion;
    }
    
    public function getLocalidad() {
        return $this->localidad;
    }
    
    public function getProvincia() {
        return $this->provincia;
    }

    public function getPais() {
        return $this->pais;
    }

    public function getCodpos() {
        return $this->codpos;
    }

    
    public function getTelefono() {
        return $this->telefono;
    }
    
    public function getEmail() {
        return $this->email;
    }
    
    public function getContrasena() {
        return $this->contrasena;
    }

    public function getRol() {
        return $this->rol;
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
                $row['apellidos'],
                $row['direccion'],
                $row['localidad'],
                $row['provincia'],
                $row['pais'],
                $row['codpos'],
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
                $row['apellidos'],
                $row['direccion'],
                $row['localidad'],
                $row['provincia'],
                $row['pais'],
                $row['codpos'],
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
