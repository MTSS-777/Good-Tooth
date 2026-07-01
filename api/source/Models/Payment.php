<?php

namespace Source\Models;

use Source\Core\Model;

class Payment extends Model
{
    private ?int $id;
    private ?int $appointmentId;
    private ?float $amount;
    private ?string $paymentMethod;
    private ?string $paymentDate;
    private ?string $status;

    public function __construct(
        ?int $id = null,
        ?int $appointmentId = null,
        ?float $amount = null,
        ?string $paymentMethod = null,
        ?string $paymentDate = null,
        ?string $status = null
    ) {
        $this->id = $id;
        $this->appointmentId = $appointmentId;
        $this->amount = $amount;
        $this->paymentMethod = $paymentMethod;
        $this->paymentDate = $paymentDate;
        $this->status = $status;

        $this->table = 'payments';
        $this->primaryKey = 'id';
        $this->fillable = [
            'appointmentId',
            'amount',
            'paymentMethod',
            'paymentDate',
            'status'
        ];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getAppointmentId(): ?int
    {
        return $this->appointmentId;
    }

    public function setAppointmentId(?int $appointmentId): void
    {
        $this->appointmentId = $appointmentId;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(?float $amount): void
    {
        $this->amount = $amount;
    }

    public function getPaymentMethod(): ?string
    {
        return $this->paymentMethod;
    }

    public function setPaymentMethod(?string $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    public function getPaymentDate(): ?string
    {
        return $this->paymentDate;
    }

    public function setPaymentDate(?string $paymentDate): void
    {
        $this->paymentDate = $paymentDate;
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