<?php

include_once 'Conexion.php';

class Captura
{
    private $id;
    private $ruta;
    private $fechaHora;

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
     * Get the value of ruta
     */
    public function getRuta()
    {
        return $this->ruta;
    }

    /**
     * Set the value of ruta
     *
     * @return  self
     */
    public function setRuta($ruta)
    {
        $this->ruta = $ruta;

        return $this;
    }

    /**
     * Get the value of fechaHora
     */
    public function getFechaHora()
    {
        return $this->fechaHora;
    }

    /**
     * Set the value of fechaHora
     *
     * @return  self
     */
    public function setFechaHora($fechaHora)
    {
        $this->fechaHora = $fechaHora;

        return $this;
    }


    public function nuevaCaptura()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('INSERT INTO video (ruta) VALUES (:ruta)');
            $pdo->bindValue(':ruta', 'https://glabsolutions.000webhostapp.com/captures/' . $this->getRuta());
            if ($pdo->execute() == 1) {
                return $conn->lastInsertId();
            }else{
                return -1 ;
            }
            $conn = null;
        } catch (Exception $e) {
        }
    }
}
