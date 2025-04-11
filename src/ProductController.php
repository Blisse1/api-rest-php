<?php

class ProductController
{
    private $gateway;

    public function __construct(ProductGateway $gateway)
    {
       $this -> gateway = $gateway; 
    }
    // the ? means that it can be null
    public function processRequest(string $method, ?string $id): void
    {
        // si hay algun id, trae uno especifico
        if($id){
            $this->processResourceRequest($method, $id);
        }else{ // sino los trae todos
            $this->processCollectionRequest($method);
        }
    }

    private function processResourceRequest(string $method, ?string $id): void
    {
        // var_dump($method, $id);
        $user = $this -> gateway -> get($id);
        if(!$user){
            http_response_code(404);
            echo json_encode([
                "message" => "User not found."
            ]);
            return;
        }
        switch ($method){
            case "GET":
                echo json_encode($user);
                break;
            case "PATCH":
                // if its null then it converts to an empy array if it isnt it just print it out
                $data = (array) $_POST;

                $errors = $this -> getValidationErrors($data);

                if(!empty($errors)){
                    http_response_code(422);
                    echo json_encode([
                        "errors" => $errors
                    ]);
                    break;
                }
                $rows = $this->gateway->update($user, $data);

                echo json_encode([
                    "message" => "Product $id updated",
                    "rows" => $rows
                ]);

                break;
        }
    }

    private function processCollectionRequest(string $method): void
    {
        switch($method){
            case "GET":
                echo json_encode($this -> gateway -> getAll());
                break;
            case "POST":
                // if its null then it converts to an empy array if it isnt it just print it out
                $data = (array) $_POST;

                $errors = $this -> getValidationErrors($data);

                if(!empty($errors)){
                    http_response_code(422);
                    echo json_encode([
                        "errors" => $errors
                    ]);
                    break;
                }
                //$data = json_decode(file_get_contents("php://input"), true); //to get an associative array
                // send data as json because thats the way we're sending it
                $id = $this->gateway->create($data);

                http_response_code(201);

                echo json_encode([
                    "message" => "Product created",
                    "id" => $id,
                ]);

                break;
            default:
                http_response_code(405);
                header("Allow: GET, POST"); // allow header included bc of specification
        }
    }

    private function getValidationErrors(array $data): array
    {
        $errors = [];

        //if(empty($data["user_status"])){
            //$errors[] = "user_status is required.";
        //}

        //if(array_key_exists("user_status", $data)){
         //   $errors[] = "name is required.";
            // if its not valid, equals to false bc 0 is not falsy
            //if(filter_var($data["user_status"], FILTER_VALIDATE_INT) === false){
                //$errors[] = "user_status must be an integer";
            //}
        //}

        return $errors;
    }
}