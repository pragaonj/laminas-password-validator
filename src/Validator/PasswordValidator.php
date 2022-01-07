<?php


namespace Pragaonj\Validator;

use Laminas\Validator\AbstractValidator;
use Traversable;

class PasswordValidator extends AbstractValidator
{
    public const MSG_NOTENOUGHCHARACTARSETS = 'msgNotEnoughCharacterSets';
    public const DIGIT = 'DIGIT';
    public const LETTER = 'LETTER';
    public const CAPITAL_LETTER = 'CAPITAL_LETTER';
    public const SPECIAL_CHARACTER = 'SPECIAL_CHARACTER';

    protected int $numberOfRequiredCharacterSets = 0;
    protected bool $digits = false;
    protected bool $letters = false;
    protected bool $capitalLetters = false;
    protected bool $specialCharacters = false;


    protected $messageTemplates = [
        self::MSG_NOTENOUGHCHARACTARSETS => 'The password does not contain enough character sets.',
    ];

    /**
     * PasswordValidator constructor.
     * Options:
     * string[] characterSets: array of character sets (possible values are: DIGIT, LETTER, CAPITAL_LETTER, SPECIAL_CHARACTER)
     * int numberOfRequiredCharacterSets: Number of sets that are required
     * @param array|Traversable $options
     */
    public function __construct($options = null)
    {
        if (is_array($options)) {
            if (isset($options['characterSets']) && is_array($options['characterSets'])) {
                foreach ($options['characterSets'] as $characterSet) {
                    switch ($characterSet) {
                        case self::DIGIT:
                            $this->digits = true;
                            break;
                        case self::LETTER:
                            $this->letters = true;
                            break;
                        case self::CAPITAL_LETTER:
                            $this->capitalLetters = true;
                            break;
                        case self::SPECIAL_CHARACTER:
                            $this->specialCharacters = true;
                            break;
                    }
                }
            }
            if (isset($options['numberOfRequiredCharacterSets'])) {
                $this->numberOfRequiredCharacterSets = $options['numberOfRequiredCharacterSets'];
            }
        }
        parent::__construct($options);
    }

    public function isValid($value)
    {
        $numberOfCharacterSets = 0;
        if ($this->digits && preg_match('/\d/', $value)) {
            $numberOfCharacterSets++;
        }
        if ($this->letters && preg_match('/[a-zäüö]/', $value)) {
            $numberOfCharacterSets++;
        }
        if ($this->capitalLetters && preg_match('/[A-ZÄÜÖ]/', $value)) {
            $numberOfCharacterSets++;
        }
        if ($this->specialCharacters && preg_match('/[^A-Za-z0-9äüöÄÜÖ]/', $value)) {
            $numberOfCharacterSets++;
        }

        if ($numberOfCharacterSets < $this->numberOfRequiredCharacterSets) {
            $this->error(self::MSG_NOTENOUGHCHARACTARSETS, $value);
            return false;
        }
        return true;
    }
}
