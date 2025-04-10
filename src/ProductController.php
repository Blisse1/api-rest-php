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
        var_dump($method, $id);
    }

    private function processCollectionRequest(string $method): void
    {
        switch($method){
            case "GET":
                echo json_encode($this -> gateway -> getAll());
                break;
        }
    }
}