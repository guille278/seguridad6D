<?php

include_once 'Conexion.php';

class Historia
{
    private $idVideo;
    private $idAlerta;


    /**
     * Get the value of idAlerta
     */
    public function getIdAlerta()
    {
        return $this->idAlerta;
    }

    /**
     * Set the value of idAlerta
     *
     * @return  self
     */
    public function setIdAlerta($idAlerta)
    {
        $this->idAlerta = $idAlerta;

        return $this;
    }

    /**
     * Get the value of idVideo
     */
    public function getIdVideo()
    {
        return $this->idVideo;
    }

    /**
     * Set the value of idVideo
     *
     * @return  self
     */
    public function setIdVideo($idVideo)
    {
        $this->idVideo = $idVideo;

        return $this;
    }

    public function insertaHistoria(){
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('INSERT INTO historia VALUES (:idVideo, :idAlerta)');
            $pdo->bindValue(':idVideo', $this->getIdVideo());
            $pdo->bindValue(':idAlerta', $this->getIdAlerta());
            if ($pdo->execute() == 1) {
                return json_encode(['Exito' => 'video y alerta almacenados correctamente.']);
            }else{
                return json_encode(['Error' => 'No se registro correctamente.']);
            }

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function verHistorial()
    {
        try {
            $conn = new Conexion();
            $pdo = $conn->prepare('SELECT a.*, v.* FROM alerta a JOIN video v JOIN historia h ON a.idAlerta = h.idAlerta AND v.idVideo = h.idVideo ORDER BY a.idAlerta DESC');
            $pdo->execute();
            while ($result = $pdo->fetch(PDO::FETCH_ASSOC)) {
                $data[] = $result;
            }
            $conn = null;
            if (!empty($data)) {
                return json_encode($data);
            }else{
                return json_encode(['Error' => 'No se encontraron registros de historial.']);
            }
        } catch (Exception $e) {
            //throw $th;
        }
    }
}
