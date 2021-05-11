<?php
include_once 'Conexion.php';

class Ubicacion
{
    private $id;
    private $nombre;



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


    public function verUbicacion()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM ubicacion');
            $pdo->execute();
            while ($result = $pdo->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $result;
            }
            $conn = null;
            if (!empty($data)) {
                return json_encode($data);
            } else {
                return json_encode(['Error' => 'No se encontraron registros de ubicaciones.']);
            }
        } catch (Exception $e) {
        }
    }

    public function buscaUbicacion($id)
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM ubicacion WHERE idUbicacion = :idUbicacion');
            $pdo->bindValue(':idUbicacion', $id);
            $pdo->execute();
            $result = $pdo->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return json_encode($result);
            } else {
                return json_encode(['Error' => 'ID no valido o no existe.']);
            }
        } catch (Exception $e) {
            //throw $th;
        }
    }
}
