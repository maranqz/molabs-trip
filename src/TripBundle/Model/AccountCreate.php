<?php
/**
 * AccountCreate
 *
 * PHP version 7.1.3
 *
 * @category Class
 * @package  TripBundle\Model
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */

/**
 * Trips
 *
 * No description provided (generated by Openapi Generator https://github.com/openapitools/openapi-generator)
 *
 * The version of the OpenAPI document: 1.0.0
 *
 * Generated by: https://github.com/openapitools/openapi-generator.git
 *
 */

/**
 * NOTE: This class is auto generated by the openapi generator program.
 * https://github.com/openapitools/openapi-generator
 * Do not edit the class manually.
 */

namespace TripBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation\Type;
use JMS\Serializer\Annotation\SerializedName;
use TripBundle\Validator\UniqueEntity;
use TripBundle\Entity\Account;

/**
 * Class representing the AccountCreate model.
 *
 * @package TripBundle\Model
 * @author  OpenAPI Generator team
 *
 * TODO blocked by https://github.com/symfony/symfony/issues/22592
 * @UniqueEntity(
 *     fields={"email"},
 *     entityClass=Account::class
 * )
 */
class AccountCreate
{
    /**
     * @var string
     * @SerializedName("email")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Email()
     * @Type("string")
     */
    protected $email;

    /**
     * @var string
     * @SerializedName("password")
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $password;

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->email = isset($data['email']) ? $data['email'] : null;
        $this->password = isset($data['password']) ? $data['password'] : null;
    }

    /**
     * Gets email.
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Sets email.
     *
     * @param string $email
     *
     * @return $this
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Gets password.
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Sets password.
     *
     * @param string $password
     *
     * @return $this
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }
}

