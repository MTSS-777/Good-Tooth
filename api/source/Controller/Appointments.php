<?php

namespace source\Controller;

use Source\Controller\Api;
use Source\Models\Appointment;

class Appointments extends Api
{

    public function validate(array $data):bool
    {
        if(
            //!isset($data['id']) ||
            !isset($data['procedure_id']) ||
            !isset($data['appointment_date']) ||
            !isset($data['appointment_time']) ||
            //empty($data['id']) ||
            empty($data['procedure_id']) ||
            empty($data['appointment_date']) ||
            empty($data['appointment_time'])
        )
        {
            return false;
        }
        return true;
    }

    public function select (array $data)
    {

        if(!$this->authToken(1))
        {
           $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
           return;
        }

         $appointment = new Appointment();

         $this->call(200, "success", "Lista de consultas", "success")->back($appointment->selectAll());
    }

    public function register (array $data): void
    {
        if(!$this->authToken(3))
        {
            $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
            return;
        }

        if(
            !isset($data['dentist_id']) ||
            !isset($data['procedure_id']) ||
            !isset($data['appointment_date']) ||
            !isset($data['appointment_time']) ||
            empty($data['dentist_id']) ||
            empty($data['procedure_id']) ||
            empty($data['appointment_date']) ||
            empty($data['appointment_time'])
        )
        {
            $this->call(400, "bad_request", "Todos os dados precisam ser enviados.", "error")->back();
            return;
        }

        $appointment = new Appointment(null, $this->userAuthId, $data["dentist_id"], $data["procedure_id"], $data["appointment_date"], $data["appointment_time"], "pending");


        var_dump($appointment);
        if(!$appointment->insert())
        {
           $this->call(500, "internal_server_error", $appointment->getErrorMessage(), "error")->back();
           return;
        }
        $response = [
            "id" => $appointment->getId(),
            "paciente" => $appointment->getPatientId(),
            "dentista" => $appointment->getDentistId()
        ];
        $this->call(201, "success", "Consulta marcada com sucesso! Aguarde a confirmação do dentista." , "success")->back($response);

    }

    public function update(array $data)
    {
        if(!$this->validate($data))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $appointment = new Appointment();

        if(!$appointment->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Consulta não encontrada", "error")->back();
            return;
        }

        $appointment->setProcedureId($data['procedure_id']);
        $appointment->setAppointmentDate($data['appointment_date']);
        $appointment->setAppointmentTime($data['appointment_time']);
        $appointment->setStatus("pending");

        if(!$appointment->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $appointment->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "id" => $appointment->getId(),
            "date" => $appointment->getAppointmentDate(),
            "time" => $appointment->getAppointmentTime()
        ];

        $this->call(201,"success","Consulta atualizada com sucesso","success")->back($response);
    }

    public function updateStatus(array $data)
    {
        
    if (!isset($data['id']) || empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
        $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
        return;
    }

        $appointment = new Appointment();
        var_dump($data['id']);
        if(!$appointment->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Consulta não encontrada", "error")->back();
            return;
        }
        $appointment->setStatus($data['status']);

        if(!$appointment->updateById($data['id']))
        {
            $this->call(
                500,
                "internal_server_error",
                $appointment->getErrorMessage(),
                "error"
            )->back();
            return;
        }

        $response = [
            "id" => $appointment->getId(),
            "date" => $appointment->getAppointmentDate(),
            "time" => $appointment->getAppointmentTime(),
            "status" => $appointment->getStatus()
        ];

        $this->call(201,"success","Consulta atualizada","success")->back($response);
    }

    public function listById(array $data)
    {
        if(!isset($data['id'])||empty($data['id'])||!filter_var($data['id'], FILTER_VALIDATE_INT))
        {
            $this->call(400, 'bad_request', "Dados incorretos ou faltando", "error")->back();
            return;
        }

        $appointment = new Appointment();

        if(!$appointment->selectById($data["id"]))
        {
            $this->call(404, "not_found", "Consulta não encontrada", "error")->back();
            return;
        }

        $response = [
            "id" => $appointment->getId(),
            "date" => $appointment->getAppointmentDate(),
            "time" => $appointment->getAppointmentTime(),
            "status" => $appointment->getStatus()
        ];

        $this->call(201,"success","Consulta encontrada","success")->back($response);
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
        $appointment = new Appointment();
        
        if(!$appointment->softDeleteById($data["id"]))
        {
            $this->call(404, "not_found", "Consulta não encontrada", "error")->back();
            return;
        }
        $this->call(201,"success","Consulta deletada","success")->back();
    }

    public function listByPatient(array $data): void
{
    
    if (!$this->authToken(1)) {
         $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
         return;
    }

    
    if (!isset($data['id']) || empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
        $this->call(400, 'bad_request', "ID do paciente inválido ou não informado.", "error")->back();
        return;
    }

    $patientId = (int) $data['id'];

    $appointment = new Appointment();

 
    $results = $appointment->selectAll(["patient_id = {$patientId}"]);

    if (empty($results)) {
        $this->call(404, "not_found", "Nenhuma consulta encontrada para este paciente.", "error")->back();
        return;
    }

    $this->call(200, "success", "Consultas do paciente encontradas.", "success")->back($results);
}

    public function listByDentist(array $data): void
    {
    if (!$this->authToken(3)) {
        $this->call(401, "unauthorized", "Token de autenticação inválido ou expirado.", "error")->back();
        return;
    }

    if (!isset($data['id']) || empty($data['id']) || !filter_var($data['id'], FILTER_VALIDATE_INT)) {
        $this->call(400, 'bad_request', "ID do dentista inválido ou não informado.", "error")->back();
        return;
    }

    $dentistId = (int) $data['id'];

    $appointment = new Appointment();

   
    $results = $appointment->selectAll(["dentist_id = {$dentistId}"]);

    if (empty($results)) {
        $this->call(404, "not_found", "Nenhuma consulta encontrada para este dentista.", "error")->back();
        return;
    }

    $this->call(200, "success", "Consultas do dentista encontradas.", "success")->back($results);
    }
}