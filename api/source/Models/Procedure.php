<?php

namespace Source\Models;

use Source\Core\Model;

class Procedure extends Model
{
    private ?int $id;
    private ?string $name;
    private ?string $description;
    private ?float $cost;
    private ?int $status;

    public function __construct(
        ?int $id = null,
        ?string $name = null,
        ?string $description = null,
        ?float $cost = null,
        ?int $status = null
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->description = $description;
        $this->cost = $cost;
        $this->status = $status;

        $this->table = 'procedures';
        $this->primaryKey = 'id';
        $this->fillable = ['name', 'description', 'cost', 'status'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCost(): ?float
    {
        return $this->cost;
    }

    public function setCost(?float $cost): void
    {
        $this->cost = $cost;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(?int $status): void
    {
        $this->status = $status;
    }
}