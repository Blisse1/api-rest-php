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

}