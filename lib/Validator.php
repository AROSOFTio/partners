<?php
class Validator
{
    public static function sanitize($value): string
    {
        $allowed = '<b><strong><i><em><u><br><p><ul><ol><li>';
        return trim(strip_tags((string)$value, $allowed));
    }

    public static function email($value): bool
    {
        return (bool)filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public static function required($value): bool
    {
        return strlen(trim((string)$value)) > 0;
    }

    public static function validateOrderRequest(array $data, array $packages): array
    {
        $errors = [];
        if (empty($packages)) {
            $errors[] = 'Please select at least one collaboration package.';
        }
        if (!self::required($data['customer_name'] ?? '')) {
            $errors[] = 'Name / Company is required.';
        }
        if (!self::email($data['customer_email'] ?? '')) {
            $errors[] = 'A valid email address is required.';
        }
        if (!self::required($data['brief'] ?? '')) {
            $errors[] = 'A short brief is required.';
        }
        $paymentType = $data['payment_type'] ?? 'full';
        if ($paymentType === 'deposit') {
            foreach ($packages as $pkg) {
                if (empty($pkg['allow_deposit'])) {
                    $errors[] = 'Deposit option is not available for one or more selected packages.';
                    break;
                }
            }
        }
        return $errors;
    }
}
