<?php

namespace Source\Controller;

use Source\Models\Faq;

class Faqs extends Api
{
    public function select(array $data): void
    {
        $faq = new Faq();
        $this->call(200, "sucess", "Lista de FAQs", "sucess")->back($faq->selectAll());
    }

    public function validate(array $data):bool
    {
        if(
            !isset($data['categoryId']) ||
            !isset($data['question']) ||
            !isset($data['answer']) ||
            empty($data['categoryId']) ||
            empty($data['question']) ||
            empty($data['answer'])||
            !filter_var($data['categoryId'], FILTER_VALIDATE_INT)
          )
          {
            return false;
          }
          return true;
    }

    public function register(array $data):void
    {
        $data = json_decode(file_get_contents("php://input"), true);

        if(!$this->validate($data))
        {
            $this->call(400, "bad_request", "Os campos question, answer e category_id são obrigatórios", "error")->back();
            return;
        }

        $faq = new Faq(null, $data['categoryId'], $data['question'], $data['answer']);

        if(!$faq->insert())
        {
            $this->call(500, "internal_server_error", $faq->getErrorMessage(), "error")->back();
        }

        $response = [
            "id" => $faq->getId(),
            "categoryId" => $faq->getCategoryId(),
            "question" => $faq->getQuestion(),
            "answer" => $faq->getAnswer()
        ];

        $this->call(201, "success", "", "success")->back($response);
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
        
        $faq = new Faq();

        if(!$faq->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Consulta não encontrada", "error")->back();
            return;
        }

        $faq->setCategoryId($data['categoryId']);
        $faq->setQuestion($data['question']);
        $faq->setAnswer($data['answer']);

        if(!$faq->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $faq->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "id" => $faq->getId(),
            "category_id" => $faq->getCategoryId(),
            "question" => $faq->getQuestion(),
            "answer" => $faq->getAnswer()
        ];

        $this->call(201,"success","Faq atualizada com sucesso","success")->back($response);
    }

    public function listById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $faq = new Faq();

        if(!$faq->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Faq não encontrada", "error")->back();
            return;
        }

        $response = [
            "id" => $faq->getId(),
            "category_id" => $faq->getCategoryId(),
            "question" => $faq->getQuestion(),
            "answer" => $faq->getAnswer()
        ];

        $this->call(201,"success","Faq encontrada","success")->back($response);
    }
    
    public function delete(array $data)
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
        
        $faq = new Faq();
        
        if(!$faq->softDeleteById($data["id"]))
        {
            $this->call(404, "not_found", "Faq não encontrada", "error")->back();
            return;
        }
        $this->call(201,"success","Faq deletada","success")->back();
    }

    public function softDeleteById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        $faq = new Faq();
        if(!$faq->softDeleteById($data["id"]))
        {
            $this->call(404, "not_found", "Faq não encontrada", "error")->back();
            return;
        }
        $this->call(201,"success","Faq deletada","success")->back();
    }
}