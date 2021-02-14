<?php

namespace TripBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trip
 *
 * @ORM\Table(name="trip", indexes={@ORM\Index(name="country_code", columns={"country_code"}), @ORM\Index(name="created_by", columns={"created_by"})})
 * @ORM\Entity
 */
class Trip
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="date", nullable=false)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="finished_at", type="date", nullable=false)
     */
    private $finishedAt;

    /**
     * @var string|null
     *
     * @ORM\Column(name="notes", type="text", length=65535, nullable=true)
     */
    private $notes;

    /**
     * @var \Account
     *
     * @ORM\ManyToOne(targetEntity="Account")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="created_by", referencedColumnName="id")
     * })
     */
    private $createdBy;

    /**
     * @var \Country
     *
     * @ORM\ManyToOne(targetEntity="Country")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_code", referencedColumnName="code")
     * })
     */
    private $countryCode;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    public function getCreatedBy(): ?Account
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?Account $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getCountryCode(): ?Country
    {
        return $this->countryCode;
    }

    public function setCountryCode(?Country $countryCode): self
    {
        $this->countryCode = $countryCode;

        return $this;
    }


}
