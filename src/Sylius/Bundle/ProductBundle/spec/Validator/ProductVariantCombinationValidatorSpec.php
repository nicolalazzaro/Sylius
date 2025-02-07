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

namespace spec\Sylius\Bundle\ProductBundle\Validator;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Sylius\Bundle\ProductBundle\Validator\Constraint\ProductVariantCombination;
use Sylius\Component\Product\Checker\ProductVariantsParityCheckerInterface;
use Sylius\Component\Product\Model\ProductInterface;
use Sylius\Component\Product\Model\ProductVariantInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

final class ProductVariantCombinationValidatorSpec extends ObjectBehavior
{
    function let(ExecutionContextInterface $context, ProductVariantsParityCheckerInterface $variantsParityChecker): void
    {
        $this->beConstructedWith($variantsParityChecker);
        $this->initialize($context);
    }

    function it_is_a_constraint_validator(): void
    {
        $this->shouldImplement(ConstraintValidator::class);
    }

    function it_does_not_add_violation_if_product_does_not_have_options(
        ExecutionContextInterface $context,
        ProductInterface $product,
        ProductVariantInterface $variant,
        ProductVariantsParityCheckerInterface $variantsParityChecker,
    ): void {
        $constraint = new ProductVariantCombination([
            'message' => 'Variant with given options already exists',
        ]);

        $variant->getProduct()->willReturn($product);

        $product->hasVariants()->willReturn(true);
        $product->hasOptions()->willReturn(false);

        $variantsParityChecker->checkParity($variant, $product)->willReturn(false);

        $context->addViolation(Argument::any())->shouldNotBeCalled();

        $this->validate($variant, $constraint);
    }

    function it_does_not_add_violation_if_product_does_not_have_variants(
        ExecutionContextInterface $context,
        ProductInterface $product,
        ProductVariantInterface $variant,
        ProductVariantsParityCheckerInterface $variantsParityChecker,
    ): void {
        $constraint = new ProductVariantCombination([
            'message' => 'Variant with given options already exists',
        ]);

        $variant->getProduct()->willReturn($product);

        $product->hasVariants()->willReturn(false);
        $product->hasOptions()->willReturn(true);

        $context->addViolation(Argument::any())->shouldNotBeCalled();

        $variantsParityChecker->checkParity($variant, $product)->willReturn(false);

        $this->validate($variant, $constraint);
    }

    function it_adds_violation_if_variant_with_given_same_options_already_exists(
        ExecutionContextInterface $context,
        ProductInterface $product,
        ProductVariantInterface $variant,
        ProductVariantsParityCheckerInterface $variantsParityChecker,
    ): void {
        $constraint = new ProductVariantCombination([
            'message' => 'Variant with given options already exists',
        ]);

        $variant->getProduct()->willReturn($product);

        $product->hasVariants()->willReturn(true);
        $product->hasOptions()->willReturn(true);

        $variantsParityChecker->checkParity($variant, $product)->willReturn(true);

        $context->addViolation('Variant with given options already exists', Argument::any())->shouldBeCalled();

        $this->validate($variant, $constraint);
    }
}
