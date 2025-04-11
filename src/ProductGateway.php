<?php

class ProductGateway
{
    private $conn;

    public function __construct(Database $database){
        $this -> conn = $database -> getConnection();
    }

    public function getAll(): array{
        $sql = "SELECT * FROM users;";
        $stmt = $this -> conn -> query($sql);
        $data = [];
        while($row = $stmt -> fetch(PDO::FETCH_ASSOC)){
            // $row["is_available"] = (bool) $row["is_available"]; If 
            // I would have a field with boolean being converted to number.
            // Id turn this on if i want it to be string (true, false)
            $data[] = $row;
        }
        return $data;
    }

    public function create(array $data): string
    {
        $sql = "INSERT INTO users (username, user_email, user_status) 
        VALUES (:username, :user_email, :user_status);";

        $stmt = $this -> conn -> prepare($sql);
        $stmt -> bindValue(":username", $data["username"], PDO::PARAM_STR);
        $stmt -> bindValue(":user_email", $data["user_email"], PDO::PARAM_STR);
        $stmt -> bindValue(":user_status", $data["user_status"], PDO::PARAM_INT);

        $stmt -> execute();

        return $this->conn->lastInsertId();
    }

    /**
        * @param string $id
        * @return array|false
        */
    public function get(string $id)
    {
        $sql = "SELECT * FROM users WHERE user_id = :id;";

        $stmt = $this -> conn -> prepare($sql);
        $stmt -> bindValue(":id", $id, PDO::PARAM_INT); // if false, check the query above

        $stmt -> execute();

        $data = $stmt -> fetch(PDO::FETCH_ASSOC); // fetch return an array if found or false if not

        return $data;
    }
    
    public function update(array $current, array $new): int
    {
        $sql = "UPDATE users 
        SET username = :username, user_status = :user_status, user_email = :user_email
        WHERE user_id=:user_id;";

        $stmt = $this -> conn -> prepare($sql);

        $stmt -> bindValue(":username", $new["username"] ?? $current["username"], PDO::PARAM_STR); 
        $stmt -> bindValue(":user_status", $new["user_status"] ?? $current["user_status"], PDO::PARAM_INT); 
        $stmt -> bindValue(":user_email", $new["user_email"] ?? $current["user_email"], PDO::PARAM_STR); 

        $stmt -> bindValue(":user_id", $current["user_id"], PDO::PARAM_INT); 

        $stmt -> execute();

        return $stmt -> rowCount();
    }
}