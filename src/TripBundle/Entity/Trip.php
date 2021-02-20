<?php

namespace TripBundle\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use TripBundle\Repository\TripRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass=TripRepository::class)
 */
class Trip
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="trips")
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=Country::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_code", referencedColumnName="code", nullable=false)
     * })
     */
    private $country;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $startedAt;

    /**
     * @ORM\Column(type="date", nullable=false)
     */
    private $finishedAt;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

    public function __construct(Account $createdBy)
    {
        $this->createdBy = $createdBy;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedBy(): ?Account
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Account $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getStartedAt(): \DateTimeInterface
    {
        return $this->startedAt;
    }

    public function setStartedAt(\DateTimeInterface $startedAt): self
    {
        $this->startedAt = $startedAt;

        return $this;
    }

    public function getFinishedAt(): ?\DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(\DateTimeInterface $finishedAt): self
    {
        $this->finishedAt = $finishedAt;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }
}
