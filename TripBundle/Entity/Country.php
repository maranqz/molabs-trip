<?php

namespace TripBundle\Entity;

use ApiPlatform\Core\Action\NotFoundAction;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use TripBundle\Repository\CountryRepository;
use TripBundle\TripBundle;

/**
 * @ApiResource(
 *     security=TripBundle::IS_TRIP_ROLE,
 *     itemOperations={"get": {
 *      "controller": NotFoundAction::class,
 *      "read": false,
 *      "output": false,
 *      "openapi_context": {
 *          "summary": "Used for IRI"
 *      },
 *     }},
 *     collectionOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass=CountryRepository::class)
 */
class Country
{
    /**
     * @ORM\Column(type="string", length=3)
     * @ORM\Id
     */
    private $code;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $region;

    public function __construct($code)
    {
        $this->code = $code;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(string $region): self
    {
        $this->region = $region;

        return $this;
    }
}
