<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BookRepository")
 */
class Book
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Name;

    /**
     * @ORM\Column(type="integer")
     */
    private $SubmitterId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Author;

    /**
     * @ORM\Column(type="integer")
     */
    private $Price;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ISPN;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->Name;
    }

    public function setName(string $Name): self
    {
        $this->Name = $Name;

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->Author;
    }

    public function setAuthor(string $Author): self
    {
        $this->Author = $Author;

        return $this;
    }

    public function getSubmitterName(): ?string
    {
        return $this->SubmitterName;
    }

    public function setSubmitterName(string $SubmitterName): self
    {
        $this->SubmitterName = $SubmitterName;

        return $this;
    }

    public function getSubmitterId(): ?int
    {
        return $this->SubmitterId;
    }

    public function setSubmitterId(int $SubmitterId): self
    {
        $this->SubmitterId = $SubmitterId;

        return $this;
    }

    public function getPrice(): ?int
    {
        return $this->Price;
    }

    public function setPrice(int $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getISPN(): ?string
    {
        return $this->ISPN;
    }

    public function setISPN(string $ISPN): self
    {
        $this->ISPN = $ISPN;

        return $this;
    }
}
