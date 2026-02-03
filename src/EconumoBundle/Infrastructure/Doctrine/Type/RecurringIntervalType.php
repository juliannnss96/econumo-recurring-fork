<?php

declare(strict_types=1);

namespace App\EconumoBundle\Infrastructure\Doctrine\Type;

use App\EconumoBundle\Domain\Entity\ValueObject\RecurringInterval;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class RecurringIntervalType extends StringType
{
    public const NAME = 'recurring_interval_type';

    public function convertToPHPValue($value, AbstractPlatform $platform): ?RecurringInterval
    {
        if ($value === null) {
            return null;
        }

        return new RecurringInterval((string) $value);
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        if ($value instanceof RecurringInterval) {
            return $value->getValue();
        }

        return null;
    }

    public function getName(): string
    {
        return self::NAME;
    }
}
