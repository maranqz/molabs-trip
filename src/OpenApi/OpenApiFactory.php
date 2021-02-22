<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;

class OpenApiFactory implements OpenApiFactoryInterface
{
    private $decorated;

    public function __construct(OpenApiFactoryInterface $decorated)
    {
        $this->decorated = $decorated;
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        $openApi->withSecurity(array_merge($openApi->getSecurity(), [
            'BasicAuth' => [],
        ]));
        $openApi->getComponents()->getSecuritySchemes()->offsetSet('BasicAuth', [
            'type' => 'http',
            'scheme' => 'basic',
        ]);

        return $openApi;
    }
}
