<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

class RegistrationStateProcessor implements ProcessorInterface
{
    public function process($data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        // Handle the state
    }
}
