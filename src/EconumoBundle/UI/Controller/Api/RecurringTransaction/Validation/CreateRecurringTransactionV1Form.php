<?php

declare(strict_types=1);

namespace App\EconumoBundle\UI\Controller\Api\RecurringTransaction\Validation;

use App\EconumoBundle\Domain\Entity\ValueObject\DecimalNumber;
use App\EconumoBundle\Domain\Entity\ValueObject\RecurringInterval;
use App\EconumoBundle\Domain\Entity\ValueObject\TransactionType;
use App\EconumoBundle\UI\Service\Validator\ValueObjectValidationFactoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\Component\Validator\Constraints\Uuid;

class CreateRecurringTransactionV1Form extends AbstractType
{
    public function __construct(private readonly ValueObjectValidationFactoryInterface $valueObjectValidationFactory)
    {
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['csrf_protection' => false]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('id', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()],
            ])
            ->add('type', ChoiceType::class, [
                'constraints' => [new NotBlank()],
                'choices' => [
                    TransactionType::EXPENSE_ALIAS,
                    TransactionType::INCOME_ALIAS,
                    TransactionType::TRANSFER_ALIAS
                ]
            ])
            ->add('amount', TextType::class, [
                'constraints' => [new NotBlank(), $this->valueObjectValidationFactory->create(DecimalNumber::class)]
            ])
            ->add('accountId', TextType::class, [
                'constraints' => [new NotBlank(), new Uuid()]
            ])
            ->add('categoryId', TextType::class, [
                'constraints' => [new Uuid()]
            ])
            ->add('startDate', TextType::class, [
                'constraints' => [new NotBlank()]
            ])
            ->add('interval', ChoiceType::class, [
                'constraints' => [new NotBlank(), $this->valueObjectValidationFactory->create(RecurringInterval::class)],
                'choices' => RecurringInterval::getAvailableValues()
            ])
            ->add('description', TextType::class, [
                'constraints' => [new Type('string'), new Length(['max' => 4096])]
            ])
            ->add('payeeId', TextType::class, [
                'constraints' => [new Uuid()]
            ])
            ->add('tagId', TextType::class, [
                'constraints' => [new Uuid()]
            ])
        ;
    }
}
