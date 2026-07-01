<?php

namespace Source\Controller;

use Source\Models\Payment;

class Payments extends Api
{
   
    public function select(array $data): void
    {
        $payment = new Payment();

        $this->call(
            200,
            "success",
            "Lista de pagamentos",
            "success"
        )->back($payment->selectAll());
    }

     public function validate(array $data): bool
    {
        if (
            !isset($data['appointmentId']) ||
            !isset($data['amount']) ||
            !isset($data['paymentMethod']) ||
            empty($data['appointmentId']) ||
            empty($data['amount']) ||
            empty($data['paymentMethod']) ||
            !filter_var($data['appointmentId'], FILTER_VALIDATE_INT)
        ) {
            return false;
        }

        $validMethods = [
            'pix',
            'credit_card',
            'debit_card',
            'cash'
        ];

        if (!in_array($data['paymentMethod'], $validMethods)) {
            return false;
        }

        return true;
    }

    public function register(array $data): void
    {
        if (!$this->validate($data)) {
            $this->call(
                400,
                "bad_request",
                "Os campos appointmentId, amount e paymentMethod são obrigatórios",
                "error"
            )->back();

            return;
        }

        $payment = new Payment(
            null,
            $data['appointmentId'],
            $data['amount'],
            $data['paymentMethod'],
            'pending'
        );

        if (!$payment->insert()) {
            $this->call(
                500,
                "internal_server_error",
                $payment->getErrorMessage(),
                "error"
            )->back();

            return;
        }

        $response = [
            "id" => $payment->getId(),
            "appointmentId" => $payment->getAppointmentId(),
            "amount" => $payment->getAmount(),
            "paymentMethod" => $payment->getPaymentMethod(),
            "status" => $payment->getStatus()
        ];

        $this->call(
            201,
            "success",
            "Pagamento cadastrado com sucesso",
            "success"
        )->back($response);
    }

    public function update(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!isset($data['status'])||empty($data['status']))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }
        
        $payment = new Payment();

        if(!$payment->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Consulta não encontrada", "error")->back();
            return;
        }

        $payment->setStatus($data['status']);

        if(!$payment->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $payment->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "id" => $payment->getId(),
            "appointment_id" => $payment->getAppointmentId(),
            "amount" => $payment->getAmount(),
            "payment_method" => $payment->getPaymentMethod(),
            "status" => $payment->getStatus()
        ];

        $this->call(201,"success","Pagamento atualizado com sucesso","success")->back($response);
    }

    public function listById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $payment = new Payment();

        if(!$payment->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Pagamento não encontrado", "error")->back();
            return;
        }

        $response = [
            "id" => $payment->getId(),
            "appointment_id" => $payment->getAppointmentId(),
            "amount" => $payment->getAmount(),
            "payment_method" => $payment->getPaymentMethod(),
            "status" => $payment->getStatus()
        ];

        $this->call(201,"success","Pagamento encontrado","success")->back($response);
    }
}