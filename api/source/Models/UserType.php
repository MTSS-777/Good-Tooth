<?php

namespace Source\Models;

use Source\Core\Model;

class UserType extends Model
{
    private ?int $id;
    private ?string $name;

    public function __construct(
        ?int $id = null,
        ?string $name = null
    ) {
        $this->id = $id;
        $this->name = $name;

        $this->table = 'users_types';
        $this->primaryKey = 'id';
        $this->fillable = ['name'];
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
}