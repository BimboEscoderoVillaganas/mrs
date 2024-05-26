<?php 

include_once('connection.php'); // Include connection class

class Database extends connection {
    // Constructor
    public function __construct(){
        parent::__construct(); // Call parent constructor
    }

    // Get single row
    public function getRow($query, $params = []){
        try {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetch();	
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());	
        }
    }

    // Get multiple rows
    public function getRows($query, $params = []){
        try {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return $stmt->fetchAll();	
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());	
        }
    }

    // Insert a row
    public function insertRow($query, $params = []){
        try {
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);
            return true;	
        } catch (PDOException $e) {
            throw new Exception($e->getMessage());	
        }
    }

    // Update a row
    public function updateRow($query, $params = []){
        return $this->insertRow($query, $params); // Assuming update is similar to insert
    }

    // Delete a row
    public function deleteRow($query, $params = []){
        return $this->insertRow($query, $params); // Assuming delete is similar to insert
    }

    // Get the last inserted ID
    public function lastID(){
        return $this->datab->lastInsertId(); 
    }

    // Transactional insert
    public function transInsert($query, $params = [], $query2, $params2 = []){
        try {
            $this->datab->beginTransaction();
            $stmt = $this->datab->prepare($query);
            $stmt->execute($params);

            $stmt2 = $this->datab->prepare($query2);
            $stmt2->execute($params2);

            $this->datab->commit();
        } catch (PDOException $e) {
            $this->datab->rollBack();
            throw new Exception($e->getMessage());	
        }
    }

    // Begin a transaction
    public function Begin(){
        $this->datab->beginTransaction();
    }

    // Commit a transaction
    public function Commit(){
        $this->datab->commit();
    }

    // Rollback a transaction
    public function RollBack(){
        $this->datab->rollBack();
    }
}

?>
