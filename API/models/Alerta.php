<?php
include_once 'Conexion.php';

class Alerta
{
    private $id;
    private $fechahora;
    private $tipo;
    private $idDispositivo;


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
     * Get the value of fechahora
     */
    public function getFechahora()
    {
        return $this->fechahora;
    }

    /**
     * Set the value of fechahora
     *
     * @return  self
     */
    public function setFechahora($fechahora)
    {
        $this->fechahora = $fechahora;

        return $this;
    }

    /**
     * Get the value of tipo
     */
    public function getTipo()
    {
        return $this->tipo;
    }

    /**
     * Set the value of tipo
     *
     * @return  self
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get the value of idDispositivo
     */
    public function getIdDispositivo()
    {
        return $this->idDispositivo;
    }

    /**
     * Set the value of idDispositivo
     *
     * @return  self
     */
    public function setIdDispositivo($idDispositivo)
    {
        $this->idDispositivo = $idDispositivo;

        return $this;
    }


    public function verAlertas()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM alerta ORDER BY idAlerta DESC LIMIT 10');
            $pdo->execute();
            while ($result = $pdo->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $result;
            }
            $conn = null;
            if (!empty($data)) {
                return json_encode($data);
            } else {
                return json_encode(['Error' => 'No se encontraron registros de alertas.']);
            }
        } catch (Exception $e) {
            //Error
        }
    }

    public function buscaAlerta($id)
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT * FROM alerta WHERE idAlerta = :idAlerta');
            $pdo->bindValue(':idAlerta', $id);
            $pdo->execute();
            $conn = null;
            $result = $pdo->fetch(PDO::FETCH_ASSOC);
            if ($result) {
                return json_encode($result);
            } else {
                return json_encode(['Error' => 'ID no valido o no existe.']);
            }
        } catch (Exception $e) {
            echo 'Error de consulta ' + $e->getMessage();
        }
    }

    public function nuevaAlerta()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('INSERT INTO alerta (tipo, idDispositivo) VALUES (:tipo, :idDispositivo)');
            $pdo->bindValue(':tipo', $this->getTipo());
            $pdo->bindValue(':idDispositivo', $this->getIdDispositivo());
            if ($pdo->execute() == 1) {
                return $conn->lastInsertId();
            } else {
                return -1;
            }
            $conn = null;
        } catch (Exception $e) {
        }
    }
}
