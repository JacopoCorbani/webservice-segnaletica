<?php
require('../src/TableGateways/CategorieGateway.php');

class CategorieController {

    private $db;
    private $requestMethod;
    private $idCategoria;

    private $categorieGateway;

    public function __construct($db, $requestMethod, $idCategoria)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->idCategoria = $idCategoria;

        $this->categorieGateway = new CategorieGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->idCategoria) {
                    $response = $this->getCategoria($this->idCategoria);
                } else {
                    $response = $this->getAllCategorie();
                };
                break;
            case 'POST':
                $response = $this->createCategoriaFromRequest();
                break;
            case 'PUT':
                $response = $this->updateCategoriaFromRequest($this->idCategoria);
                break;
            case 'DELETE':
                $response = $this->deleteCategoria($this->idCategoria);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAllCategorie()
    {
        $result = $this->categorieGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getCategoria($id)
    {
        $result = $this->categorieGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createCategoriaFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validaCategoria($input)) {
            return $this->unprocessableEntityResponse($input);
        }
        $this->categorieGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateCategoriaFromRequest($id)
    {
        $result = $this->categorieGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validaCategoria($input)) {
            return $this->unprocessableEntityResponse($input);
        }
        $this->categorieGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteCategoria($id)
    {
        $result = $this->categorieGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->segnaliGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validaCategoria($input)
    {
        if (! isset($input['nome'])) {
            return false;
        }
        return true;
    }

    private function unprocessableEntityResponse($str)
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input',
            'request' => $str
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
}