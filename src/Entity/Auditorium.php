<?php

namespace App\Entity;

use App\Repository\AuditoriumRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AuditoriumRepository::class)
 */
class Auditorium
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=32)
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $seats_no;

    /**
     * @ORM\OneToMany(targetEntity=Seat::class, mappedBy="auditorium", orphanRemoval=true)
     */
    private $seats;

    public function __construct()
    {
        $this->seats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSeatsNo(): ?int
    {
        return $this->seats_no;
    }

    public function setSeatsNo(int $seats_no): self
    {
        $this->seats_no = $seats_no;

        return $this;
    }

    /**
     * @return Collection<int, Seat>
     */
    public function getSeats(): Collection
    {
        return $this->seats;
    }

    public function addSeat(Seat $seat): self
    {
        if (!$this->seats->contains($seat)) {
            $this->seats[] = $seat;
            $seat->setAuditorium($this);
        }

        return $this;
    }

    public function removeSeat(Seat $seat): self
    {
        if ($this->seats->removeElement($seat)) {
            // set the owning side to null (unless already changed)
            if ($seat->getAuditorium() === $this) {
                $seat->setAuditorium(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getName();
    }
}
