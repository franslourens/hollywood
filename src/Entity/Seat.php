<?php

namespace App\Entity;

use App\Repository\SeatRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeatRepository::class)
 */
class Seat
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     */
    private $row;

    /**
     * @ORM\Column(type="integer")
     */
    private $number;

    /**
     * @ORM\ManyToOne(targetEntity=Auditorium::class, inversedBy="seats")
     * @ORM\JoinColumn(nullable=false)
     */
    private $auditorium;

    /**
     * @ORM\OneToMany(targetEntity=Seatreserved::class, mappedBy="seat_id", orphanRemoval=true)
     */
    private $seatreserved;

    public function __construct()
    {
        $this->seatreserved = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRow(): ?int
    {
        return $this->row;
    }

    public function setRow(int $row): self
    {
        $this->row = $row;

        return $this;
    }

    public function getNumber(): ?int
    {
        return $this->number;
    }

    public function setNumber(int $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getAuditorium(): ?auditorium
    {
        return $this->auditorium;
    }

    public function setAuditorium(?auditorium $auditorium): self
    {
        $this->auditorium = $auditorium;

        return $this;
    }

    /**
     * @return Collection<int, Seatreserved>
     */
    public function getSeatreserved(): Collection
    {
        return $this->seatreserved;
    }

    public function addSeatreserved(Seatreserved $seatreserved): self
    {
        if (!$this->seatreserved->contains($seatreserved)) {
            $this->seatreserved[] = $seatreserved;
            $seatreserved->setSeatId($this);
        }

        return $this;
    }

    public function removeSeatreserved(Seatreserved $seatreserved): self
    {
        if ($this->seatreserved->removeElement($seatreserved)) {
            // set the owning side to null (unless already changed)
            if ($seatreserved->getSeatId() === $this) {
                $seatreserved->setSeatId(null);
            }
        }

        return $this;
    }
}
