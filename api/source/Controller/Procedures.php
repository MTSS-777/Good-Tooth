<?php

namespace Source\Controller;

use Source\Models\Procedure;

class Procedures extends Api
{
    public function validate(array $data): bool
    {
        if (
            !isset($data['name']) ||
            !isset($data['description']) ||
            !isset($data['cost']) ||
            empty($data['name']) ||
            empty($data['description']) ||
            empty($data['cost']) ||
            !is_numeric($data['cost'])
        ) {
            return false;
        }

        return true;
    }

    public function select(array $data): void
    {
        $procedure = new Procedure();

        $this->call(
            200,
            "success",
            "Lista de procedimentos",
            "success"
        )->back($procedure->selectAll());
    }

    public function register(array $data): void
    {
        if(!$this->authToken(1))
        {
            $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
            return;
        }

        if (!$this->validate($data)) {
            $this->call(
                400,
                "bad_request",
                "Nome, custo e descrição são obrigatórios",
                "error"
            )->back();

            return;
        }

        $procedure = new Procedure(
            null,
            $data['name'],
            $data['description'],
            (float)$data['cost']
        );

        if (!$procedure->insert()) {
            $this->call(
                500,
                "internal_server_error",
                $procedure->getErrorMessage(),
                "error"
            )->back();

            return;
        }

        $response = [
            "id" => $procedure->getId(),
            "name" => $procedure->getName(),
            "description" => $procedure->getDescription(),
            "cost" => $procedure->getCost()
        ];

        $this->call(
            201,
            "success",
            "Procedimento cadastrado com sucesso",
            "success"
        )->back($response);
    }

    public function update(array $data)
    {
        if(!$this->authToken(1)){
            $this->call(
                401,
                "unauthorized",
                "Usuário não está autenticado (sem token ou token inválido).",
                "error")->back();
            return;
        }

        if(!$this->validate($data) || !isset($data['id']) || empty($data['id']))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        
        $procedure = new Procedure();

        if(!$procedure->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Procedimento não encontrado", "error")->back();
            return;
        }

        $procedure->setName($data['name']);
        $procedure->setDescription($data['description']);
        $procedure->setCost((float)$data['cost']);

        if(!$procedure->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $procedure->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "id" => $procedure->getId(),
            "name" => $procedure->getName(),
            "description" => $procedure->getDescription(),
            "cost" => $procedure->getCost()
        ];

        $this->call(201,"success"," Procedimento atualizado com sucesso","success")->back($response);
    }

    public function listById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $procedure = new Procedure();

        if(!$procedure->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Procedimento não encontrado", "error")->back();
            return;
        }

         $response = [
            "id" => $procedure->getId(),
            "name" => $procedure->getName(),
            "description" => $procedure->getDescription(),
            "cost" => $procedure->getCost()
        ];

        $this->call(201,"success","Procedimento encontrado","success")->back($response);
    }
   
    public function softDeleteById(array $data)
    {
        if(!$this->authToken(1))
        {
            $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
            return;
        }

      if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        $procedure = new Procedure();
        
        if(!$procedure->softDeleteById($data["id"]))
        {
            $this->call(404, "not_found", "Procedimento não encontrado", "error")->back();
            return;
        }
        $this->call(201,"success","Procedimento deletado","success")->back();
    }
}