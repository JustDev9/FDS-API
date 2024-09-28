<?php

interface CrudInterface{
    public function getAll();
    public function getOne();
    public function insert();
    public function update();
    public function delete();
}

class CRUD{

    protected $pdo;

    public function __construct($pdo){
        $this->pdo = $pdo;
    }

    public function getAll(){
        $sql = "SELECT * FROM users";
        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute()){
                $data =  $stmt->fetchAll();
                if ($stmt->rowCount() > 0){
                    return $data;
                }else{
                    return 'There are no data present';
                }
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    } 

    public function getOne($data){
        $sql = "SELECT * FROM users WHERE id= ?";
        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->id])){
                $data =  $stmt->fetchAll();
                if ($stmt->rowCount() > 0){
                    return $data;
                }else{
                    return 'User does not exist';
                }
            }
        }
        catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function insert($data){
        $sql = 'INSERT INTO users(firstname, lastname, is_admin) VALUES(?, ?, Default)';

        if (!isset($data->firstname) || !isset($data->lastname)) {
            return "Error: FirstName and LastName are required fields.";
        }

        if (empty($data->firstname) || empty($data->lastname)) {
            return "Error: FirstName and LastName cannot be empty.";
        }

        try{
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->firstname, $data->lastname])){
                return 'Data Successfully Inserted';
            }else{
                return 'Data Unsuccessfully Inserted';
            }
        }catch(PDOException $e){
            echo $e->getMessage();
        }
    }

    public function update($data) {
        $sql = "UPDATE users SET is_admin = CASE WHEN is_admin = 0 THEN 1 WHEN is_admin = 1 THEN 0 END WHERE id = ?";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->id])) {
                return "Data Successfully Updated";
            } else {
                return "Data Unsuccessfully Updated";
            }
        } catch (PDOException $e) {
            return $e->getMessage();  
        }
    }
    
    public function delete($data){
        $sql = "DELETE FROM users WHERE id = ?";
    
        try {
            $stmt = $this->pdo->prepare($sql);
            if ($stmt->execute([$data->id])) {
                return "User successfully deleted.";
            } else {
                return "Failed to delete user.";
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }
    }
}