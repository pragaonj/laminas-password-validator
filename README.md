# laminas-password-validator

laminas-password-validator provides a validator for character-set based input validation.

## Installation

`composer require pragaonj/laminas-password-validator`

## Usage

The password validator implements laminas `Laminas\Validator\ValidatorInterface` and can be used like every other laminas validator. The validator requires two options:

- `characterSets`
  contains an array of considered character-sets (possible values are: _DIGIT, LETTER, CAPITAL_LETTER, SPECIAL_CHARACTER_)
- `numberOfRequiredCharacterSets`
  the number of considered character-sets that need to be present in the password.

```php
$validator = new PasswordValidator([
    "characterSets" => 
    [
        PasswordValidator::SPECIAL_CHARACTER,
        PasswordValidator::LETTER,
        PasswordValidator::CAPITAL_LETTER,
        PasswordValidator::DIGIT,
    ],
    "numberOfRequiredCharacterSets" => 4, // requires all 4 character-sets to be present in the password
]);

$valid = $validator->isValid("myInsecurePassword");

$messages = $validator->getMessages();
```

To overwrite the default error message you can set the messageTemplate for `msgNotEnoughCharacterSets`.

```php
$validator = new PasswordValidator([
    "characterSets" => 
    [
        PasswordValidator::SPECIAL_CHARACTER,
        PasswordValidator::LETTER,
        PasswordValidator::CAPITAL_LETTER,
        PasswordValidator::DIGIT,
    ],
    "numberOfRequiredCharacterSets" => 4,
    "messageTemplates" => [
        PasswordValidator::MSG_NOTENOUGHCHARACTARSETS => "my custom error message"
    ]
]);

$valid = $validator->isValid("myInsecurePassword");

$messages = $validator->getMessages();
// will return ["msgNotEnoughCharacterSets" => "my custom error message"]
```