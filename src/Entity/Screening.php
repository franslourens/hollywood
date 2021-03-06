<?php

namespace App\Entity;

use App\Repository\ScreeningRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ScreeningRepository::class)
 */
class Screening
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Movie::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $movie_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $start;

    /**
     * @ORM\ManyToOne(targetEntity=Auditorium::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $auditorium_id;

    /**
     * @ORM\OneToMany(targetEntity=Reservation::class, mappedBy="screening_id", orphanRemoval=true)
     */
    private $reservations;

    /**
     * @ORM\OneToMany(targetEntity=Seatreserved::class, mappedBy="screening_id", orphanRemoval=true)
     */
    private $seatreserveds;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->seatreserveds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMovieId(): ?movie
    {
        return $this->movie_id;
    }

    public function setMovieId(?movie $movie_id): self
    {
        $this->movie_id = $movie_id;

        return $this;
    }

    public function getStart(): ?\DateTimeInterface
    {
        return $this->start;
    }

    public function setStart(\DateTimeInterface $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function getAuditoriumId(): ?Auditorium
    {
        return $this->auditorium_id;
    }

    public function setAuditoriumId(?Auditorium $auditorium_id): self
    {
        $this->auditorium_id = $auditorium_id;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations[] = $reservation;
            $reservation->setScreeningId($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getScreeningId() === $this) {
                $reservation->setScreeningId(null);
            }
        }

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
            $seatreserved->setScreeningId($this);
        }

        return $this;
    }

    public function removeSeatreserved(Seatreserved $seatreserved): self
    {
        if ($this->seatreserveds->removeElement($seatreserved)) {
            // set the owning side to null (unless already changed)
            if ($seatreserved->getScreeningId() === $this) {
                $seatreserved->setScreeningId(null);
            }
        }

        return $this;
    }

    public function can_cancel() {

      $pastDate = new \DateTime();
      $tosub = new \DateInterval('PT4H');
      $pastDate->sub($tosub);

      $firstDate = $this->getStart();

      //check if screening date has already passed
      if ($firstDate < $pastDate) {
          return false;
      }

      $secondDate = new \DateTime("now");

      $diff = $firstDate->diff($secondDate);
      $diffHours = $diff->h;

      $diffInHours = $diffHours + ($diff->days * 24);

      if($diffInHours >= 2) {
        return true;
      }

      return false;
    }

    public function __toString() {
        return ucwords($this->auditorium_id) . " @ " . $this->getStart()->format("Y-m-d H:i:s");
    }
}
