<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Serializer\Filter\PropertyFilter;
use App\Doctrine\TripSetCreatedByListener;
use App\Kernel;
use App\Repository\TripRepository;
use App\Validator\NotOverlapping;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ApiResource(
 *     normalizationContext={"groups"={"trip:read"}, "datetime_format"="Y-m-d"},
 *     denormalizationContext={"groups"={"trip:write"}, "datetime_format"="Y-m-d"},
 *     collectionOperations={
 *          "get"={"security"=Kernel::IS_TRIP_ROLE},
 *          "post"={"security"=Kernel::IS_TRIP_ROLE}
 *     },
 *     itemOperations={
 *          "get"={"security"=Trip::GRANTED},
 *          "put"={"security"=Trip::GRANTED},
 *          "delete"={"security"=Trip::GRANTED}
 *     }
 * )
 * @ApiFilter(SearchFilter::class, properties={"country"})
 * @ApiFilter(PropertyFilter::class)
 * @ApiFilter(DateFilter::class, properties={"startedAt", "finishedAt"})
 * @ORM\Entity(repositoryClass=TripRepository::class)
 * @ORM\Table(indexes={
 *     @Index(columns={"started_at"}),
 *     @Index(columns={"finished_at"})
 * })
 * @ORM\EntityListeners({TripSetCreatedByListener::class})
 * @NotOverlapping()
 */
class Trip
{
    const GRANTED = Kernel::IS_TRIP_ROLE.' and object.getCreatedBy() == user';

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Account::class, inversedBy="trips", cascade={"remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @Groups({"trip:read", "trip:write"})
     * @Assert\NotBlank()
     * @ORM\ManyToOne(targetEntity=Country::class)
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="country_code", referencedColumnName="code", nullable=false)
     * })
     */
    private $country;

    /**
     * @var \DateTimeInterface
     *
     * @ApiProperty(attributes={
     *     "openapi_context"={"format"="date"}
     * })
     * @Groups({"trip:read", "trip:write"})
     * @Assert\NotNull()
     * @Assert\Type("\DateTimeInterface")
     * @ORM\Column(type="date", nullable=false)
     */
    private $startedAt;

    /**
     * @var \DateTimeInterface
     *
     * @ApiProperty(attributes={
     *     "openapi_context"={"format"="date"}
     * })
     * @Groups({"trip:read", "trip:write"})
     * @Assert\NotNull()
     * @Assert\Type("\DateTimeInterface")
     * @Assert\GreaterThan(propertyPath="startedAt")
     * @ORM\Column(type="date", nullable=false)
     */
    private $finishedAt;

    /**
     * @Groups({"trip:read", "trip:write"})
     * @Assert\Length(max="65536")
     * @ORM\Column(type="text", nullable=true)
     */
    private $notes;

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
        $this->startedAt = $startedAt->setTime(0, 0, 0);

        return $this;
    }

    public function getFinishedAt(): \DateTimeInterface
    {
        return $this->finishedAt;
    }

    public function setFinishedAt(\DateTimeInterface $finishedAt): self
    {
        $this->finishedAt = $finishedAt->setTime(0, 0, 0);

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
