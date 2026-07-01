<?php

namespace Source\Controller;

use Source\Models\UserType;

class UserTypes extends Api
{
    public function validate(array $data): bool
    {
        if (
            !isset($data['name']) ||
            empty(trim($data['name']))
        ) {
            return false;
        }

        return true;
    }

    public function select(array $data): void
    {
        $userType = new UserType();

        $this->call(
            200,
            "success",
            "Lista de tipos de usuário",
            "success"
        )->back($userType->selectAll());
    }

    public function register (array $data): void
    {
        if(!$this->authToken(1)){
            $this->call(
                401,
                "unauthorized",
                "Usuário não está autenticado (sem token ou token inválido).",
                "error")->back();
            return;
        }

        if (!$this->validate($data)) {
            $this->call(
                400,
                "bad_request",
                "O campo name é obrigatório",
                "error"
            )->back();

            return;
        }

        $userType = new UserType(
            null,
            $data['name']
        );

        if (!$userType->insert()) {
            $this->call(
                500,
                "internal_server_error",
                $userType->getErrorMessage(),
                "error"
            )->back();

            return;
        }

        $response = [
            "id" => $userType->getId(),
            "name" => $userType->getName()
        ];

        $this->call(
            201,
            "success",
            "Tipo de usuário cadastrado com sucesso",
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
        
        if(!$this->validate($data)||!isset($data['id'])||empty($data['id']))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        
        $userType = new UserType();

        if(!$userType->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Consulta não encontrada", "error")->back();
            return;
        }

        $userType->setName($data['name']);

        if(!$userType->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $userType->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "id" => $userType->getId(),
            "name" => $userType->getName(),
        ];

        $this->call(201,"success","Tipo de usuário atualizado com sucesso","success")->back($response);
    }

    public function listById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $userType = new UserType();

        if(!$userType->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Tipo de usuário não encontrado", "error")->back();
            return;
        }

        $response = [
            "id" => $userType->getId(),
            "name" => $userType->getName(),
        ];

        $this->call(201,"success","Tipo de usuário encontrado","success")->back($response);
    }
}