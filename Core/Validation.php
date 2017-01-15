<?php

namespace lolita\LolitaFramework\Core;

use Countable;

class Validation
{
    /**
     * Runs a validation rule on a single passed data.
     *
     * @param mixed $data  The given data: string, int, array, bool...
     * @param array $rules The rules to use for validation.
     *
     * @return mixed
     */
    public static function single($data, array $rules)
    {
        foreach ($rules as $rule) {
            // Parse $rule and check for attributes.
            $ruleProperties = self::parseRule($rule);

            // Set rule method.
            $signature = sprintf('validate%s', ucwords($ruleProperties['rule']));

            // Check if the datas given is an array
            // If array, parse each item and return them
            // into the array.
            if (is_array($data)) {
                // Overwrite each array value
                foreach ($data as $key => $value) {
                    // Validate the data value.
                    $data[$key] = self::$signature($value, $ruleProperties['attributes']);
                }
            } else {
                // The data is a string or single value.
                $data = self::$signature($data, $ruleProperties['attributes']);
            }
        }

        return $data;
    }

    /**
     * Validate multiple inputs.
     *
     * @param array $data
     * @param array $rules
     *
     * @return array
     */
    public static function multiple(array $data, array $rules)
    {
        $validates = [];

        foreach ($rules as $field => $fieldRules) {
            $input = Arr::get($data, $field);

            $validates[$field] = self::single($input, $fieldRules);
        }

        return $validates;
    }

    /**
     * Parse validation rule and return an array containing the rule and its attributes.
     *
     * @param string $rule The validation rule to parse.
     *
     * @return array
     */
    public static function parseRule($rule)
    {
        $properties = [
            'rule' => '',
            'attributes' => [],
        ];

        // Check if attributes are defined...
        if (0 < strpos($rule, ':')) {
            $extract = explode(':', $rule);

            // The rule
            $properties['rule'] = $extract[0];

            // The attributes
            $properties['attributes'] = self::getAttributes($extract[1]);
        } else {
            // No attributes, simply defined the rule.
            // Leave attributes as empty array.
            $properties['rule'] = $rule;
        }

        return $properties;
    }

    /**
     * Return the defined attributes.
     *
     * @param string $attributes The string of attributes.
     *
     * @return array
     */
    public static function getAttributes($attributes)
    {
        // If comma, get a list of attributes
        if (0 < strpos($attributes, ',')) {
            $attributes = explode(',', $attributes);
            $attributes = array_map(
                function ($att) {
                    return trim($att);
                },
                $attributes
            );
        } else {
            // No comma, only one attribute
            $attributes = [trim($attributes)];
        }

        return $attributes;
    }

    /**
     * Check if a given array is associative.
     *
     * @param array $arr
     *
     * @return bool True if associative.
     */
    public static function isAssociative(array $arr)
    {
        if (empty($arr)) {
            return false;
        }
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Validate a value with only alphabetic characters.
     *
     * @param string $data       The data to validate.
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateAlpha($data, array $attributes = [])
    {
        return ctype_alpha($data) ? $data : '';
    }

    /**
     * Validate a value with only numeric characters.
     *
     * @param string $data       The data to validate.
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateNum($data, array $attributes = [])
    {
        return ctype_digit($data) ? $data : '';
    }

    /**
     * Validate a negative full number.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateNegnum($data, array $attributes = [])
    {
        $data = (int) $data;

        return (0 > $data) ? (string) $data : '';
    }

    /**
     * Validate a value with alphanumeric characters.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateAlnum($data, array $attributes = [])
    {
        return ctype_alnum($data) ? $data : '';
    }

    /**
     * Validate a text field value.
     *
     * @param string $data       The data to validate.
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateTextfield($data, array $attributes = [])
    {
        return sanitize_text_field($data);
    }

    /**
     * Encode a textarea value.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateTextarea($data, array $attributes = [])
    {
        return esc_textarea($data);
    }

    /**
     * Encode a HTML value.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateHtml($data, array $attributes = [])
    {
        return esc_html($data);
    }

    /**
     * Validate an email value.
     *
     * @param string $data       The data to validate.
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateEmail($data, array $attributes = [])
    {
        $email = sanitize_email($data);

        return is_email($email) ? $email : '';
    }

    /**
     * Validate a URL value.
     *
     * @param string $data       The URL to validate.
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateUrl($data, array $attributes = [])
    {
        if (!empty($attributes)) {
            return esc_url($data, $attributes);
        }

        return esc_url($data);
    }

    /**
     * Validate a MIN length of string.
     *
     * @param string $data       The string to evaluate.
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateMin($data, array $attributes = [])
    {
        // If no length defined, return empty string.
        // @TODO Log the lack of a length...
        if (empty($attributes)) {
            return '';
        }

        $length = $attributes[0];
        $data = trim($data);

        if ($length <= strlen($data)) {
            return $data;
        }

        return '';
    }

    /**
     * Validate a MAX length of string.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateMax($data, array $attributes = [])
    {
        // If no length defined, return empty string.
        // @TODO Log the lack of a length...
        if (empty($attributes)) {
            return '';
        }

        $length = $attributes[0];
        $data = trim($data);

        if ($length >= strlen($data)) {
            return $data;
        }

        return '';
    }

    /**
     * Validate a boolean value.
     * Return TRUE for '1', 'on', 'yes', 'true'. Else FALSE.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateBool($data, array $attributes = [])
    {
        return filter_var($data, FILTER_VALIDATE_BOOLEAN, ['flags' => FILTER_NULL_ON_FAILURE]) ? $data : '';
    }

    /**
     * Strips Evil Scripts.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateKses($data, array $attributes = [])
    {
        if (empty($attributes)) {
            return '';
        }

        $allowedHtml = self::ksesAllowedHtml($attributes);

        return wp_kses($data, $allowedHtml);
    }

    /**
     * Set the allowed HTML tags for kses validation.
     *
     * @param array $attributes
     *
     * @return array
     */
    public static function ksesAllowedHtml(array $attributes)
    {
        $params = [];

        foreach ($attributes as $atts) {
            $atts = explode('|', $atts);

            // Set the HTML tag.
            $key = array_shift($atts);
            $params[$key] = [];

            // Add tag attributes.
            if (!empty($atts)) {
                foreach ($atts as $attribute) {
                    $params[$key][$attribute] = [];
                }
            }
        }

        return $params;
    }

    /**
     * Validate an hexadecimal value.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateHex($data, array $attributes = [])
    {
        return ctype_xdigit($data) ? $data : '';
    }

    /**
     * Validate a color hexadecimal value.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateColor($data, array $attributes = [])
    {
        return preg_match('/#([a-f]|[A-F]|[0-9]){3}(([a-f]|[A-F]|[0-9]){3})?\b/', $data) ? $data : '';
    }

    /**
     * Validate a file extension.
     *
     * @param string $data
     * @param array  $attributes
     *
     * @return string
     */
    public static function validateFile($data, array $attributes = [])
    {
        $ext = pathinfo($data, PATHINFO_EXTENSION);

        return (in_array($ext, $attributes)) ? $data : '';
    }

    /**
     * Validate a required data.
     *
     * @param string|array $data
     * @param array        $attributes
     *
     * @return string|array
     */
    public static function validateRequired($data, array $attributes = [])
    {
        if (is_null($data)) {
            return '';
        } elseif (is_string($data) && trim($data) === '') {
            return '';
        } elseif ((is_array($data) || $data instanceof Countable) && count($data) < 1) {
            return [];
        }

        return $data;
    }
}
