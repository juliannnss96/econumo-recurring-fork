<?php

declare(strict_types=1);

namespace App\EconumoBundle\Domain\Entity\ValueObject;

use InvalidArgumentException;

final class RecurringInterval implements ValueObjectInterface
{
    public const MONTHLY = 'monthly';
    public const YEARLY = 'yearly';
    public const DAILY = 'daily';
    public const WEEKLY = 'weekly';

    private string $value;

    public function __construct(string $value)
    {
        if (!in_array($value, self::getAvailableValues())) {
            throw new InvalidArgumentException(sprintf('Invalid interval value: %s', $value));
        }

        $this->value = $value;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function isEqual(ValueObjectInterface $valueObject): bool
    {
        if (!$valueObject instanceof self) {
            return false;
        }

        return $this->getValue() === $valueObject->getValue();
    }

    public static function getAvailableValues(): array
    {
        return [
            self::MONTHLY,
            self::YEARLY,
            self::DAILY,
            self::WEEKLY,
        ];
    }

    public static function validate($value): void
    {
        if (!in_array($value, self::getAvailableValues())) {
            throw new InvalidArgumentException(sprintf('Invalid interval value: %s', $value));
        }
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
