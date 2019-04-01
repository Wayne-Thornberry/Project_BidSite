<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CommentRepository")
 */
class Comment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @ORM\OneToOne(
     *     targetEntity="app\Entity\User"
     * )
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    private $UserId;

    /**
     * @ORM\Column(type="integer")
     * @ORM\OneToOne(
     *     targetEntity="app\Entity\Book"
     * )
     * @ORM\JoinColumn(name="book_id", referencedColumnName="id")
     * @ORM\Column(type="integer")
     */
    private $BookId;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Text;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?int
    {
        return $this->UserId;
    }

    public function setUserId(int $UserId): self
    {
        $this->UserId = $UserId;

        return $this;
    }

    public function getBookId(): ?int
    {
        return $this->BookId;
    }

    public function setBookId(int $BookId): self
    {
        $this->BookId = $BookId;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->Text;
    }

    public function setText(string $Text): self
    {
        $this->Text = $Text;

        return $this;
    }
}
