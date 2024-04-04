<?php
require('../src/TableGateways/SegnaliGateway.php');

class SegnaliController {

    private $db;
    private $requestMethod;
    private $idSegnale;

    private $segnaliGateway;

    public function __construct($db, $requestMethod, $idSegnale)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->idSegnale = $idSegnale;

        $this->segnaliGateway = new SegnaliGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->idSegnale) {
                    $response = $this->getSegnale($this->idSegnale);
                } else {
                    $response = $this->getAllSegnali();
                };
                break;
            case 'POST':
                $response = $this->createSegnaleFromRequest();
                break;
            case 'PUT':
                $response = $this->updateSegnaleFromRequest($this->idSegnale);
                break;
            case 'DELETE':
                $response = $this->deleteSegnale($this->idSegnale);
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

    private function getAllSegnali()
    {
        $result = $this->segnaliGateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getSegnale($id)
    {
        $result = $this->segnaliGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function createSegnaleFromRequest()
    {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateSegnale($input)) {
            return $this->unprocessableEntityResponse($input);
        }
        $this->segnaliGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    private function updateSegnaleFromRequest($id)
    {
        $result = $this->segnaliGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if (! $this->validateSegnale($input)) {
            return $this->unprocessableEntityResponse($input);
        }
        $this->segnaliGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function deleteSegnale($id)
    {
        $result = $this->segnaliGateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->segnaliGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }

    private function validateSegnale($input)
    {
        if (! isset($input['nome'])) {
            return false;
        }
        if (! isset($input['id_categoria'])) {
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