<?php

declare(strict_types=1);

namespace App\UI\Http\Rest\Internal\DTO\Auth;


use App\Domain\Core\Auth\Validator\Constrains\RevokeTokenRequestConstraint;
use Symfony\Component\Validator\Constraints\GroupSequence;

#[GroupSequence(['RevokeTokenRequest', 'Custom'])]
#[RevokeTokenRequestConstraint(groups: ['Custom'])]
class RevokeTokenRequest
{

}
