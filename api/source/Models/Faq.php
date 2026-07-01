<?php
namespace Source\Models;


use Source\Core\Model;


class Faq extends Model
{
    private ?int $id;
    private ?int $categoryId;
    private ?string $question;
    private ?string $answer;
    private ?int $status;


    public function __construct(
        ?int $id = null,
        ?int $categoryId = null,
        ?string $question = null,
        ?string $answer = null,
        ?int $status = null
    ) {
        $this->id = $id;
        $this->categoryId = $categoryId;
        $this->question = $question;
        $this->answer = $answer;
        $this->status = $status;


        $this->table = 'faqs';
        $this->primaryKey = 'id';
        $this->fillable = ['categoryId', 'question', 'answer', 'status'];
    }


    public function getId(): ?int
    {
        return $this->id;
    }


    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }


    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }


    public function setCategoryId(?int $categoryId): self
    {
        $this->categoryId = $categoryId;
        return $this;
    }


    public function getQuestion(): ?string
    {
        return $this->question;
    }


    public function setQuestion(?string $question): self
    {
        $this->question = $question;
        return $this;
    }


    public function getAnswer(): ?string
    {
        return $this->answer;
    }


    public function setAnswer(?string $answer): self
    {
        $this->answer = $answer;
        return $this;
    }


    public function getStatus(): ?int
    {
        return $this->status;
    }


    public function setStatus(?int $status): self
    {
        $this->status = $status;
        return $this;
    }
}
