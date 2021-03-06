<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReservationRepository::class)
 */
class Reservation
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Screening::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $screening_id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user_reserved_id;

    /**
     * @ORM\ManyToOne(targetEntity=ReservationType::class, inversedBy="reservations")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reservation_type_id;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    private $contact;

    /**
     * @ORM\Column(type="boolean")
     */
    private $reserved;

    /**
     * @ORM\Column(type="integer")
     */
    private $user_paid_id;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $paid;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $active;

    /**
     * @ORM\OneToMany(targetEntity=Seatreserved::class, mappedBy="reservation_id", orphanRemoval=true)
     */
    private $seatreserveds;

    /**
     * @ORM\Column(type="string", length=255, unique=true)
     */
    private $code;

    public function __construct()
    {
        $this->seatreserveds = new ArrayCollection();
        $this->code = substr(str_shuffle(str_repeat('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',10-2)),0,10-2);
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getUserReservedId(): ?User
    {
        return $this->user_reserved_id;
    }

    public function setUserReservedId(?User $user_reserved_id): self
    {
        $this->user_reserved_id = $user_reserved_id;

        return $this;
    }

    public function getReservationTypeId(): ?ReservationType
    {
        return $this->reservation_type_id;
    }

    public function setReservationTypeId(?ReservationType $reservation_type_id): self
    {
        $this->reservation_type_id = $reservation_type_id;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getReserved(): ?bool
    {
        return $this->reserved;
    }

    public function setReserved(bool $reserved): self
    {
        $this->reserved = $reserved;

        return $this;
    }

    public function getUserPaidId(): ?int
    {
        return $this->user_paid_id;
    }

    public function setUserPaidId(int $user_paid_id): self
    {
        $this->user_paid_id = $user_paid_id;

        return $this;
    }

    public function getPaid(): ?int
    {
        return $this->paid;
    }

    public function setPaid(?int $paid): self
    {
        $this->paid = $paid;

        return $this;
    }

    public function getActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(?bool $active): self
    {
        $this->active = $active;

        return $this;
    }

    /**
     * @return Collection<int, Seatreserved>
     */
    public function getSeatreserveds(): Collection
    {
        return $this->seatreserveds;
    }

    public function addSeatreserved(Seatreserved $seatreserved): self
    {
        if (!$this->seatreserveds->contains($seatreserved)) {
            $this->seatreserveds[] = $seatreserved;
            $seatreserved->setReservationId($this);
        }

        return $this;
    }


    public function removeSeatreserved(Seatreserved $seatreserved): self
    {
        if ($this->seatreserveds->removeElement($seatreserved)) {
            // set the owning side to null (unless already changed)
            if ($seatreserved->getReservationId() === $this) {
                $seatreserved->setReservationId(null);
            }
        }

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public static function collection($reservations) {

      $data = [];

      foreach($reservations as $key => $reservation) {

        $screening = $reservation->getScreeningId();
        $data[$key]["movie"] = $screening->getMovieId()->__toString();
        $data[$key]["cinema"] = $screening->__toString();
        $data[$key]["reference"] = $reservation->getCode();
        $data[$key]["cancel"] = $screening->can_cancel();
        $data[$key]["seats"] = [];

        foreach ($reservation->getSeatreserveds() as $seatReserved) {
              $seat = $seatReserved->getSeatId();
              $data[$key]["seats"]["Row"] = $seat->getRow();
              $data[$key]["seats"]["Number"] = $seat->getNumber();
        }

      }

      return $data;
    }

    public static function can_book($reservations, $seats) {
      $taken_seats = [];

      foreach($reservations as $reserved) {
        $taken_seats[] = $reserved->getSeatId()->getId();
      }

      $screening_seats = [];

      foreach($seats as $s) {
         $screening_seats[] = $s->getId();
      }

      $not_taken = array_diff($screening_seats, $taken_seats);

      return $not_taken;
    }
}
