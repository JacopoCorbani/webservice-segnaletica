<?php

class SegnaliGateway {

    private $db = null;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $statement = "
            SELECT 
                id, nome, descrizione, id_categoria, percorso_immagine
            FROM
                segnali;
        ";

        try {
            $statement = $this->db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function find($id)
    {
        $statement = "
            SELECT 
                id, nome, descrizione, id_categoria, percorso_immagine
            FROM
                segnali
            WHERE id = ?;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array($id));
            $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
            return $result;
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO segnali 
                (nome, descrizione, id_categoria, percorso_immagine)
            VALUES
                (:nome, :descrizione, :id_categoria, :percorso_immagine);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'nome'  => $input['nome'],
                'descrizione' => isset($input['descrizione']) ? $input['descrizione'] : null,
                'id_categoria' => isset($input['id_categoria']) ? $input['id_categoria'] : null,
                'percorso_immagine' => isset($input['percorso_immagine']) ? $input['percorso_immagine'] : null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE segnali
            SET 
                id = :id,
                nome  = :nome,
                descrizione = :descrizione,
                id_categoria = :id_categoria,
                percorso_immagine = :percorso_immagine
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => $input['id'],
                'nome'  => $input['nome'],
                'descrizione' => isset($input['descrizione']) ? $input['descrizione'] : null,
                'id_categoria' => isset($input['id_categoria']) ? $input['id_categoria'] : null,
                'percorso_immagine' => isset($input['percorso_immagine']) ? $input['percorso_immagine'] : null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM segnali
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }    
    }
}