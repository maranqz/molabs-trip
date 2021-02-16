<?php
/**
 * TripsApiInterface
 * PHP version 7.1.3
 *
 * @category Class
 * @package  TripBundle
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

namespace TripBundle\Api;

use TripBundle\Model\Date;
use TripBundle\Model\Filter;
use TripBundle\Model\TripCreate;
use TripBundle\Model\TripUpdate;

/**
 * TripsApiInterface Interface Doc Comment
 *
 * @category Interface
 * @package  TripBundle\Api
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */
interface TripsApiInterface
{

    /**
     * Sets authentication method BasicAuth
     *
     * @param string $value Value of the BasicAuth authentication method.
     *
     * @return void
     */
    public function setBasicAuth($value);

    /**
     * Operation createTrip
     *
     * Create trip
     *
     * @param  TripBundle\Model\TripCreate $tripCreate  Data of new trip (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return TripBundle\Model\Trip
     *
     */
    public function createTrip(TripCreate $tripCreate, &$responseCode, array &$responseHeaders);

    /**
     * Operation deleteTrip
     *
     * Delete trip
     *
     * @param  int $tripId   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return TripBundle\Model\DefaultResponse
     *
     */
    public function deleteTrip($tripId, &$responseCode, array &$responseHeaders);

    /**
     * Operation getTrip
     *
     * Get trip information
     *
     * @param  int $tripId   (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return TripBundle\Model\Trip
     *
     */
    public function getTrip($tripId, &$responseCode, array &$responseHeaders);

    /**
     * Operation getTrips
     *
     * Get trips
     *
     * @param  Filter $filter   (optional)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return TripBundle\Model\Trip[]
     *
     */
    public function getTrips($filter = null, &$responseCode, array &$responseHeaders);

    /**
     * Operation updateTrip
     *
     * Update trip information
     *
     * @param  int $tripId   (required)
     * @param  TripBundle\Model\TripUpdate $tripUpdate  Updatable data of trip (required)
     * @param  integer $responseCode     The HTTP response code to return
     * @param  array   $responseHeaders  Additional HTTP headers to return with the response ()
     *
     * @return TripBundle\Model\Trip
     *
     */
    public function updateTrip($tripId, TripUpdate $tripUpdate, &$responseCode, array &$responseHeaders);
}
