<?php
declare(strict_types=1);

namespace App\Service\Validator;

/**
 * Class TypeNotificationValidator
 * @package App\Service\Validator
 */
class TypeNotificationValidator implements ValidatorInterface
{
    /**
     * @param $value
     * @return bool
     */
    public static function validate($value): bool
    {
        return ($value !== 'alert' && $value !== 'new_message');
    }
}
