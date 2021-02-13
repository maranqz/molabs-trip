<?php
/**
 * TripBundle
 *
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

namespace TripBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TripBundle\DependencyInjection\Compiler\TripApiPass;

/**
 * TripBundle Class Doc Comment
 *
 * @category Class
 * @package  TripBundle
 * @author   OpenAPI Generator team
 * @link     https://github.com/openapitools/openapi-generator
 */
class TripBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new TripApiPass());
    }
}
