<?php

include_once 'Conexion.php';

class Usuario
{
    private $id;
    private $email;
    private $password;
    private $telefono;
    private $nombre;
    private $apellidoP;
    private $apellidoM;
    private $direccion;
    private $estado;


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @return  self
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @return  self
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get the value of telefono
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set the value of telefono
     *
     * @return  self
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get the value of nombre
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set the value of nombre
     *
     * @return  self
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get the value of apellidoP
     */
    public function getApellidoP()
    {
        return $this->apellidoP;
    }

    /**
     * Set the value of apellidoP
     *
     * @return  self
     */
    public function setApellidoP($apellidoP)
    {
        $this->apellidoP = $apellidoP;

        return $this;
    }

    /**
     * Get the value of apellidoM
     */
    public function getApellidoM()
    {
        return $this->apellidoM;
    }

    /**
     * Set the value of apellidoM
     *
     * @return  self
     */
    public function setApellidoM($apellidoM)
    {
        $this->apellidoM = $apellidoM;

        return $this;
    }

    /**
     * Get the value of direccion
     */
    public function getDireccion()
    {
        return $this->direccion;
    }

    /**
     * Set the value of direccion
     *
     * @return  self
     */
    public function setDireccion($direccion)
    {
        $this->direccion = $direccion;

        return $this;
    }
    /**
     * Get the value of estado
     */
    public function getEstado()
    {
        return $this->estado;
    }

    /**
     * Set the value of estado
     *
     * @return  self
     */
    public function setEstado($estado)
    {
        $this->estado = $estado;

        return $this;
    }

    public function verUsuarios()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM usuario');
            $pdo->execute();
            while ($result = $pdo->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $result;
            }
            $conn = null;
            if (!empty($data)) {
                return json_encode($data);
            } else {
                return json_encode(['Error' => 'No se encontraron registros de usuarios.']);
            }
        } catch (Exception $e) {
            echo 'Error de consulta ' + $e->getMessage();
        }
    }

    public function buscaUsuario($id)
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM usuario WHERE idUsuario = :idUsuario');
            $pdo->bindValue(':idUsuario', $id);
            $pdo->execute();
            $result = $pdo->fetch(PDO::FETCH_ASSOC);
            $conn = null;
            if ($result) {
                return json_encode($result);
            } else {
                return json_encode(['Error' => 'ID no valido o no existe.']);
            }
        } catch (Exception $e) {
            echo 'Error de consulta ' + $e->getMessage();
        }
    }

    public function nuevoUsuario()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('INSERT INTO usuario (email, password, telefono, nombre, apellidoP, apellidoM, direccion) VALUES (:email, :pass, :telefono, :nombre, :apellidoP, :apellidoM, :direccion)');
            $pdo->bindValue(':email', $this->getEmail());
            $pdo->bindValue(':pass', MD5($this->getPassword()));
            $pdo->bindValue(':telefono', $this->getTelefono());
            $pdo->bindValue(':nombre', $this->getNombre());
            $pdo->bindValue(':apellidoP', $this->getApellidoP());
            $pdo->bindValue(':apellidoM', $this->getApellidoM());
            $pdo->bindValue(':direccion', $this->getDireccion());
            if ($pdo->execute() == 1) {
                return json_encode(['Exito' => 'Usuario registrado con exito.']);
            } else {
                return json_encode(['Error' => 'Error al registrar el usuario.']);
            }
            $conn = null;
        } catch (Exception $e) {
            echo 'Error de consulta ' + $e->getMessage();
        }
    }

    public function editaUsuario($id)
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('UPDATE usuario SET email = :email, password = MD5(:pass), telefono = :telefono, nombre = :nombre, apellidoP = :apellidoP, apellidoM = :apellidoM, direccion = :direccion, estado = :estado WHERE idUsuario = :idUsuario');
            $pdo->bindValue(':email', $this->getEmail());
            $pdo->bindValue(':pass', $this->getPassword());
            $pdo->bindValue(':telefono', $this->getTelefono());
            $pdo->bindValue(':nombre', $this->getNombre());
            $pdo->bindValue(':apellidoP', $this->getApellidoP());
            $pdo->bindValue(':apellidoM', $this->getApellidoM());
            $pdo->bindValue(':direccion', $this->getDireccion());
            $pdo->bindValue(':estado', $this->getEstado());
            $pdo->bindValue('idUsuario', $id);
            if ($pdo->execute() == 1) {
                return json_encode(['Exito' => 'Usuario actualizado con exito.']);
            } else {
                return json_encode(['Error' => 'Error al actualizar el usuario.']);
            }
            $conn = null;
        } catch (Exception $e) {
            echo 'Error de consulta ' . $e->getMessage();
        }
    }
    public function eliminaUsuario($id)
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('DELETE FROM usuario WHERE idUsuario = :idUsuario');
            $pdo->bindValue(':idUsuario', $id);
            if ($pdo->execute() == 1) {
                return json_encode(['Exito' => 'Usuario eliminado con exito.']);
            } else {
                return json_encode(['Error' => 'Error al eliminar el usuario.']);
            }
            $conn = null;
        } catch (Exception $e) {
            echo 'Error de consulta ' . $e->getMessage();
        }
    }
}
