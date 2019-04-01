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
     * @ORM\Column(type="integer")
     */
    private $BookId;

    /**
     * @ORM\Column(type="integer")
     * @ORM\OneToOne(
     *     targetEntity="app\Entity\User",
     * )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $UserId;

    /**
     * @ORM\Column(type="float")
     *
     */
    private $Price;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBookId(): ?int
    {
        return $this->BookId;
    }

    public function getUserId(): ?int
    {
        return $this->UserId;
    }

    public function setBookId(int $BookId): self
    {
        $this->BookId = $BookId;

        return $this;
    }

    public function setUserId(int $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
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
}
