<?php


namespace Source\Models;


use Source\Core\Model;


class Appointment extends Model
{
    private ?int $id;
    private ?int $patientId;
    private ?int $dentistId;
    private ?int $procedureId;
    private ?string $appointmentDate;
    private ?string $appointmentTime;
    private ?string $status;


    public function __construct(?int $id = null, ?int $patientId = null, ?int $dentistId = null, ?int $procedureId = null, ?string $appointmentDate = null, ?string $appointmentTime = null, ?string $status = null)
    {
        $this->id = $id;
        $this->patientId = $patientId;
        $this->dentistId = $dentistId;
        $this->procedureId = $procedureId;
        $this->appointmentDate = $appointmentDate;
        $this->appointmentTime = $appointmentTime;
        $this->status = $status;


        $this->table = 'appointments';
        $this->primaryKey = 'id';
        $this->fillable = ['patientId', 'dentistId', 'procedureId', 'appointmentDate', 'appointmentTime', 'status'];
    }




    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id):void
    {
        $this->id = $id;
    }


    public function setPatientId(?int $patientId): void
    {
        $this->patientId = $patientId;
    }




    public function getPatientId(): ?int
    {
        return $this->patientId;
    }




    public function setDentistId(?int $dentistId): void
    {
        $this->dentistId = $dentistId;
    }




    public function getDentistId(): ?int
    {
        return $this->dentistId;
    }




    public function getProcedureId(): ?int
    {
        return $this->procedureId;
    }




    public function setProcedureId(?int $procedureId): void
    {
        $this->procedureId = $procedureId;
    }




    public function getAppointmentDate(): ?string
    {
        return $this->appointmentDate;
    }




    public function setAppointmentDate(?string $appointmentDate): void
    {
        $this->appointmentDate = $appointmentDate;
    }




    public function getAppointmentTime(): ?string
    {
        return $this->appointmentTime;
    }




    public function setAppointmentTime(?string $appointmentTime): void
    {
        $this->appointmentTime = $appointmentTime;
    }




    public function getStatus(): ?string
    {
        return $this->status;
    }




    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }
}
