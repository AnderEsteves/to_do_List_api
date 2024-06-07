<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use Exception;

class Tasks extends ResourceController{
  
  private $tasksModel;
  private $token = '1234';

  //metodo construtor
  public function __construct()
  {
    $this->tasksModel = new \App\Models\TaskModel();
  }

  private function _validaToken(){

    return $this->request->getHeaderLine('token') == $this->token;
  }

  //metodo GET
  public function list(){

    $data = $this->tasksModel->findAll();

    return $this->response->setJSON($data);
  }

  //metodo GET BY ID
  public function getById($id){
    $response = [];
    $task = $this->tasksModel->find($id);

    if ($task) {
      $response = [
        'response' => 'success',
        'data' => $task,
      ];
    } else {
      $response = [
        'response' => 'error',
        'msg' => 'Tarefa não encontrada',
      ];
    }
    return $this->response->setJSON($response);
  }


  public function create(){
    $response = [];

    // Validar Token
    if ($this->_validaToken() == true) {
      $insertTask = $this->request->getJSON(true);  // Lê o corpo da requisição JSON e converte para array

      try {
        // Verificação dos dados recebidos
        log_message('debug', 'Dados recebidos para inserção: ' . json_encode($insertTask));

        if ($this->tasksModel->insert($insertTask)) {
          $response = [
            'response' => 'success',
            'msg' => 'Task criada',
          ];
        } else {
          $response = [
            'response' => 'error',
            'msg' => 'Erro ao criar',
            'erros' => $this->tasksModel->errors()
          ];
        }
      } catch (Exception $e) {
        $response = [
          'response' => 'error',
          'msg' => 'Erro ao criar',
          'erros' => [
            'exception' => $e->getMessage()
          ]
        ];
      }
    } else {
      $response = [
        'response' => 'error',
        'msg' => 'Token inválido',
      ];
    }
    return $this->response->setJSON($response);
  }


  //METODO PUT
  public function updateById($id){
    $response = [];

    // Validar Token
    if ($this->_validaToken() == true) {
      $updatedTask = $this->request->getJSON(true);  // Lê o corpo da requisição JSON e converte para array

      try {
        // Verificação dos dados recebidos
        log_message('debug', 'Dados recebidos para atualização: ' . json_encode($updatedTask));

        if ($this->tasksModel->update($id, $updatedTask)) {
          $response = [
            'response' => 'success',
            'msg' => 'Task atualizada',
          ];
        } else {
          $response = [
            'response' => 'error',
            'msg' => 'Erro ao atualizar',
            'erros' => $this->tasksModel->errors()
          ];
        }
      } catch (Exception $e) {
        $response = [
          'response' => 'error',
          'msg' => 'Erro ao atualizar',
          'erros' => [
            'exception' => $e->getMessage()
          ]
        ];
      }
    } else {
      $response = [
        'response' => 'error',
        'msg' => 'Token inválido',
      ];
    }
    return $this->response->setJSON($response);
  }


  // Método DELETE 
  public function deleteById($id){
    $response = [];

    // Validar Token
    if ($this->_validaToken() == true) {
      try {
        if ($this->tasksModel->delete($id)) {
          $response = [
            'response' => 'success',
            'msg' => 'Tarefa excluída',
          ];
        } else {
          $response = [
            'response' => 'error',
            'msg' => 'Erro ao excluir tarefa',
          ];
        }
      } catch (Exception $e) {
        $response = [
          'response' => 'error',
          'msg' => 'Erro ao excluir tarefa',
          'erros' => [
            'exception' => $e->getMessage()
          ]
        ];
      }
    } else {
      $response = [
        'response' => 'error',
        'msg' => 'Token inválido',
      ];
    }

    return $this->response->setJSON($response);
  }
}

