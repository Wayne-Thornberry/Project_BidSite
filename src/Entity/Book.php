<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\OrderBy;

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
     * @ORM\Column(type="string", length=255)
     */
    private $Author;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $ISBN;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $User;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $DateSubmitted;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Bid", mappedBy="Book")
     * @OrderBy({"Price" = "DESC"})
     */
    private $Bids;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Comment", mappedBy="Book", orphanRemoval=true)
     */
    private $Comments;

    /**
     * @ORM\Column(type="float")
     */
    private $StartingBid;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $Description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $IsOpen;

    public function __construct()
    {
        $this->Bids = new ArrayCollection();
        $this->Comments = new ArrayCollection();
    }

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

    public function getISBN(): ?string
    {
        return $this->ISBN;
    }

    public function setISBN(string $ISBN): self
    {
        $this->ISBN = $ISBN;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }

    public function getDateSubmitted(): ?\DateTimeInterface
    {
        return $this->DateSubmitted;
    }

    public function setDateSubmitted(?\DateTimeInterface $DateSubmitted): self
    {
        $this->DateSubmitted = $DateSubmitted;

        return $this;
    }

    /**
     * @return Collection|Bid[]
     */
    public function getBids(): Collection
    {
        return $this->Bids;
    }

    public function addHighestBid(Bid $highestBid): self
    {
        if (!$this->Bids->contains($highestBid)) {
            $this->Bids[] = $highestBid;
            $highestBid->setBook($this);
        }

        return $this;
    }

    public function removeHighestBid(Bid $highestBid): self
    {
        if ($this->Bids->contains($highestBid)) {
            $this->Bids->removeElement($highestBid);
            // set the owning side to null (unless already changed)
            if ($highestBid->getBook() === $this) {
                $highestBid->setBook(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Comment[]
     */
    public function getComments(): Collection
    {
        return $this->Comments;
    }

    public function addComment(Comment $comment): self
    {
        if (!$this->Comments->contains($comment)) {
            $this->Comments[] = $comment;
            $comment->setBook($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->Comments->contains($comment)) {
            $this->Comments->removeElement($comment);
            // set the owning side to null (unless already changed)
            if ($comment->getBook() === $this) {
                $comment->setBook(null);
            }
        }

        return $this;
    }

    public function getStartingBid(): ?float
    {
        return $this->StartingBid;
    }

    public function setStartingBid(float $StartingBid): self
    {
        $this->StartingBid = $StartingBid;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->Description;
    }

    public function setDescription(string $Description): self
    {
        $this->Description = $Description;

        return $this;
    }

    public function getIsOpen(): ?bool
    {
        return $this->IsOpen;
    }

    public function setIsOpen(bool $IsOpen): self
    {
        $this->IsOpen = $IsOpen;

        return $this;
    }
}
