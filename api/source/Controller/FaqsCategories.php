<?php

namespace Source\Controller;

use Source\Models\FaqCategory;

class FaqsCategories extends Api
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
        $faqCategory = new FaqCategory();

        $this->call(
            200,
            "success",
            "Lista de categorias FAQ",
            "success"
        )->back($faqCategory->selectAll());
    }

    public function register(array $data): void
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

        $faqCategory = new FaqCategory(
            null,
            $data['name']
        );

        if (!$faqCategory->insert()) {
            $this->call(
                500,
                "internal_server_error",
                $faqCategory->getErrorMessage(),
                "error"
            )->back();

            return;
        }

        $response = [
            "id" => $faqCategory->getId(),
            "name" => $faqCategory->getName()
        ];

        $this->call(
            201,
            "success",
            "Categoria cadastrada com sucesso",
            "success"
        )->back($response);
    }

    public function update(array $data)
    {
         if(!$this->authToken(1))
        {
            $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
            return;
        }
        
        if(!$this->validate($data)||!isset($data['id'])||empty($data['id']))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        
        $faqCategory = new FaqCategory();

        if(!$faqCategory->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Categoria não encontrada", "error")->back();
            return;
        }

        $faqCategory->setName($data['name']);

        if(!$faqCategory->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $faqCategory->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "id" => $faqCategory->getId(),
            "name" => $faqCategory->getName(),
        ];

        $this->call(201,"success","Categoria atualizada com sucesso","success")->back($response);
    }

    public function listById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $faqCategory = new FaqCategory();

        if(!$faqCategory->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Categoria não encontrada", "error")->back();
            return;
        }

        $response = [
            "id" => $faqCategory->getId(),
            "name" => $faqCategory->getName(),
        ];

        $this->call(201,"success","Categoria encontrada","success")->back($response);
    }
}