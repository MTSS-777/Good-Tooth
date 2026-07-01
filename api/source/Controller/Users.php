<?php

namespace Source\Controller;

use Source\Models\User;
use Source\Models\Appointment;

class Users extends Api
{

    public function validate(array $data):bool
    {
        if(
            !isset($data['name']) ||
            !isset($data['email']) ||
            empty($data['name']) ||
            empty($data['email']) ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL)
        )
        {
            return false;
        }
        return true;
    }

    public function validateType(array $data):bool
    {
        if(
            !isset($data['type_id']) ||
            empty($data['type_id'])
        )
        {
            return false;
        }
        return true;
    }

    public function select(array $data): void
    {
        if(!$this->authToken(1)){
            $this->call(
                401,
                "unauthorized",
                "Usuário não está autenticado (sem token ou token inválido).",
                "error")->back();
            return;
        }

        $user = new User();

        $this->call(
            200,
            "success",
            "Lista de usuarios",
            "success"
        )->back($user->selectAll());
    }

    public function register (array $data): void
    {
        if(!isset($data['password']) || empty($data['password'])) {
            $this->call(400,
                "bad_request",
                "A senha é obrigatória.",
                "error")->back();
            return;
        }

        if(!$this->validate($data)){
            $this->call(400,
                "bad_request",
                "Nome e e-mail são obrigatórios. O e-mail deve ser válido.",
                "error")->back();
            return;
        }

        if(!$this->validateType($data)){
            $this->call(400,
                "bad_request",
                "Tipo de usuario é obrigatório.",
                "error")->back();
            return;
        }

        $user = new User(
            null,
            $data['type_id'],
            $data['name'],
            $data['email'],
            $data['password'],
            1
        );

        if(!$user->insert()) {
            $this->call(500, "internal_server_error", $user->getErrorMessage(), "error")->back();
            return;
        }

        $response = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];

        $this->call(201,"success","Usuário inserido com sucesso","created")->back($response);
    }


    public function registerAdmin (array $data): void
    {
        if(!isset($data['password']) || empty($data['password'])) {
            $this->call(400,
                "bad_request",
                "A senha é obrigatória.",
                "error")->back();
            return;
        }

        if(!$this->validate($data)){
            $this->call(400,
                "bad_request",
                "Nome e e-mail são obrigatórios. O e-mail deve ser válido.",
                "error")->back();
            return;
        }

        $user = new User(
            null,
            1,
            $data['name'],
            $data['email'],
            $data['password'],
            1
        );

        if(!$user->insert()) {
            $this->call(500, "internal_server_error", $user->getErrorMessage(), "error")->back();
            return;
        }

        $response = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];

        $this->call(201,"success","Dentista inserido com sucesso","created")->back($response);
    }

    public function loginPatient (array $data): void
    {
        if(!isset($data['email'], $data['password']) ||
            empty($data['email']) || empty($data['password']) ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->call(
                400,
                "bad_request",
                "E-mail e senha são obrigatórios. O e-mail deve ser válido.",
                "error")->back();
            return;
        }

        $user = new User();
        if(!$user->loginPatient($data['email'], $data['password'])) {
            $this->call(
                401,
                "unauthorized",
                $user->getErrorMessage(),
                "error")->back();
            return;
        }

        $response = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "token" => $user->getToken()
        ];

        $this->call(
            200,
            "success",
            "Usuário logado com sucesso",
            "success")->back($response);
    }

    public function loginDentist (array $data): void
    {
        if(!isset($data['email'], $data['password']) ||
            empty($data['email']) || empty($data['password']) ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->call(
                400,
                "bad_request",
                "E-mail e senha são obrigatórios. O e-mail deve ser válido.",
                "error")->back();
            return;
        }

        $user = new User();
        if(!$user->loginDentist($data['email'], $data['password'])) {
            $this->call(
                401,
                "unauthorized",
                $user->getErrorMessage(),
                "error")->back();
            return;
        }

        $response = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "token" => $user->getToken()
        ];

        $this->call(
            200,
            "success",
            "Usuário logado com sucesso",
            "success")->back($response);
    }

    public function loginAdmin (array $data): void
    {
        if(!isset($data['email'], $data['password']) ||
            empty($data['email']) || empty($data['password']) ||
            !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->call(
                400,
                "bad_request",
                "E-mail e senha são obrigatórios. O e-mail deve ser válido.",
                "error")->back();
            return;
        }

        $user = new User();
        if(!$user->loginAdmin($data['email'], $data['password'])) {
            $this->call(
                401,
                "unauthorized",
                $user->getErrorMessage(),
                "error")->back();
            return;
        }

        $response = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "token" => $user->getToken()
        ];

        $this->call(
            200,
            "success",
            "Usuário logado com sucesso",
            "success")->back($response);
    }

    public function update(array $data)
    {
        if(!$this->authToken(3))
        {
            $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
            return;
        }

        $data['id'] = $this->userAuthId;

        if(!isset($data['id'])||empty($data['id'])||!isset($data['name'])||empty($data['name'])||!isset($data['password'])||empty($data['password']))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        
        $user = new user();

        if(!$user->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Usuario não encontrada", "error")->back();
            return;
        }

        $user->setName($data['name']);
        $password = password_hash($data['name'], PASSWORD_DEFAULT);
        $user->setPassword($password);

        if(!$user->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $user->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];

        $this->call(201,"success","Usuário atualizado com sucesso","success")->back($response);
    }

    public function updateDentist(array $data)
    {
        if(!$this->authToken(1))
        {
            $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
            return;
        }

        if(!isset($data['id'])||empty($data['id'])||!isset($data['status'])||empty($data['status']))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        
        $user = new user();

        if(!$user->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Usuario não encontrada", "error")->back();
            return;
        }

        $user->setStatus($data['status']);

        if(!$user->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $user->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];

        $this->call(201,"success","Usuário atualizado com sucesso","success")->back($response);
    }

    public function updateAdmin(array $data)
    {
        if(!$this->authToken(1))
        {
            $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
            return;
        }

        $data['id'] = $this->userAuthId;

        if(!$this->validate($data) || !isset($data['password']) ||empty($data['password']))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        
        $user = new user();

        if(!$user->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Usuario não encontrada", "error")->back();
            return;
        }

        $user->setName($data["name"]);
        $user->setEmail($data["email"]);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $user->setPassword($password);

        if(!$user->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $user->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];

        $this->call(201,"success","Usuário atualizado com sucesso","success")->back($response);
    }

    public function listById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $user = new User();

        if(!$user->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Usuário não encontrado", "error")->back();
            return;
        }

        $response = [
            "type_id" => $user->getTypeId(),
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];

        $this->call(201,"success","Usuário encontrado","success")->back($response);
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
        $user = new User();
        
        if(!$user->softDeleteById($data["id"]))
        {
            $this->call(404, "not_found", "Usuário não encontrado", "error")->back();
            return;
        }
        $this->call(201,"success","Usuário deletado","success")->back();
    }

    public function appointmentDentist(array $data)
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

        $user = new User();

        if(!$user->appointmentByDentist($data['id']))
        {
            $this->call(404, "not_found", "Usuário não encontrado", "error")->back();
            return;
        }

        $response = [
            "id" => $user->getId(),
            "name" => $user->getName(),
            "email" => $user->getEmail()
        ];

        $this->call(201,"success","Usuário encontrado","success")->back($response);
    }
}

