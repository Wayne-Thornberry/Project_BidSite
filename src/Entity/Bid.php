<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BidRepository")
 */
class Bid
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(
     *     targetEntity="app\Entity\Book"
     * )
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     * @ORM\Column(type="float")
     */
    private $Price;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $User;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DatePosted;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Book", inversedBy="HighestBid")
     * @ORM\JoinColumn(nullable=false, onDelete="CASCADE")
     */
    private $Book;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrice(): ?float
    {
        return $this->Price;
    }

    public function setPrice(float $Price): self
    {
        $this->Price = $Price;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getDatePosted(): ?\DateTimeInterface
    {
        return $this->DatePosted;
    }

    public function setDatePosted(?\DateTimeInterface $DatePosted): self
    {
        $this->DatePosted = $DatePosted;

        return $this;
    }

    public function getBook(): ?Book
    {
        return $this->Book;
    }

    public function setBook(?Book $Book): self
    {
        $this->Book = $Book;

        return $this;
    }
}
