<?php

namespace App\Entity;

use App\Repository\SeatreservedRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SeatreservedRepository::class)
 */
class Seatreserved
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=seat::class, inversedBy="seatreserved")
     * @ORM\JoinColumn(nullable=false)
     */
    private $seat_id;

    /**
     * @ORM\ManyToOne(targetEntity=reservation::class, inversedBy="seatreserveds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation_id;

    /**
     * @ORM\ManyToOne(targetEntity=screening::class, inversedBy="seatreserveds")
     * @ORM\JoinColumn(nullable=false)
     */
    private $screening_id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSeatId(): ?seat
    {
        return $this->seat_id;
    }

    public function setSeatId(?seat $seat_id): self
    {
        $this->seat_id = $seat_id;

        return $this;
    }

    public function getReservationId(): ?reservation
    {
        return $this->reservation_id;
    }

    public function setReservationId(?reservation $reservation_id): self
    {
        $this->reservation_id = $reservation_id;

        return $this;
    }

    public function getScreeningId(): ?screening
    {
        return $this->screening_id;
    }

    public function setScreeningId(?screening $screening_id): self
    {
        $this->screening_id = $screening_id;

        return $this;
    }
}
