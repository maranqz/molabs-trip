<?php
/**
 * Country
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

/**
 * Class representing the Country model.
 *
 * @package TripBundle\Model
 * @author  OpenAPI Generator team
 */
class Country 
{
        /**
     * @var string|null
     * @SerializedName("name")
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $name;

    /**
     * @var string|null
     * @SerializedName("code")
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $code;

    /**
     * @var string|null
     * @SerializedName("region")
     * @Assert\Type("string")
     * @Type("string")
     */
    protected $region;

    /**
     * Constructor
     * @param mixed[] $data Associated array of property values initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->name = isset($data['name']) ? $data['name'] : null;
        $this->code = isset($data['code']) ? $data['code'] : null;
        $this->region = isset($data['region']) ? $data['region'] : null;
    }

    /**
     * Gets name.
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Sets name.
     *
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name = null)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Gets code.
     *
     * @return string|null
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Sets code.
     *
     * @param string|null $code
     *
     * @return $this
     */
    public function setCode($code = null)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Gets region.
     *
     * @return string|null
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * Sets region.
     *
     * @param string|null $region
     *
     * @return $this
     */
    public function setRegion($region = null)
    {
        $this->region = $region;

        return $this;
    }
}

