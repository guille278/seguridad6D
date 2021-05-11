<?php
include_once 'Conexion.php';

class Dispositivo
{
    private $id;
    private $nombre;
    private $ip;
    private $estado;
    private $idUsuario;
    private $idUbicacion;



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
     * Get the value of ip
     */
    public function getIp()
    {
        return $this->ip;
    }

    /**
     * Set the value of ip
     *
     * @return  self
     */
    public function setIp($ip)
    {
        $this->ip = $ip;

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

    /**
     * Get the value of idUsuario
     */
    public function getIdUsuario()
    {
        return $this->idUsuario;
    }

    /**
     * Set the value of idUsuario
     *
     * @return  self
     */
    public function setIdUsuario($idUsuario)
    {
        $this->idUsuario = $idUsuario;

        return $this;
    }

    /**
     * Get the value of idUbicacion
     */
    public function getIdUbicacion()
    {
        return $this->idUbicacion;
    }

    /**
     * Set the value of idUbicacion
     *
     * @return  self
     */
    public function setIdUbicacion($idUbicacion)
    {
        $this->idUbicacion = $idUbicacion;

        return $this;
    }

    public function verDispositivos()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM dispositivo');
            $pdo->execute();
            while ($result = $pdo->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $result;
            }
            $conn = null;
            if (!empty($data)) {
                return json_encode($data);
            } else {
                return json_encode(['Error' => 'No se encontraron registros de dispositivos.']);
            }
        } catch (Exception $e) {
            echo 'Error de consulta ' + $e->getMessage();
        }
    }

    public function buscaDispositivo($id)
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM dispositivos WHERE idDispositivo = :idDispositivo');
            $pdo->bindValue(':idDispositivo', $id);
            $pdo->execute();
            $result = $pdo->fetch(PDO::FETCH_ASSOC);
            $conn = null;
            if ($result) {
                return json_encode($result);
            } else {
                return json_encode(['Error' => 'ID no valido o no existe.']);
            }
        } catch (Exception $e) {
            
        }
    }
}
