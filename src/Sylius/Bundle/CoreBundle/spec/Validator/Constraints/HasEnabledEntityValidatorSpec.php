<?php

/*
 * This file is part of the Sylius package.
 *
 * (c) Sylius Sp. z o.o.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace spec\Sylius\Bundle\CoreBundle\Validator\Constraints;

use Doctrine\Persistence\ManagerRegistry;
use PhpSpec\ObjectBehavior;
use Symfony\Component\Validator\ConstraintValidator;

final class HasEnabledEntityValidatorSpec extends ObjectBehavior
{
    public function let(ManagerRegistry $registry)
    {
        $this->beConstructedWith($registry);
    }

    public function it_is_a_constraint_validator()
    {
        $this->shouldHaveType(ConstraintValidator::class);
    }
}
