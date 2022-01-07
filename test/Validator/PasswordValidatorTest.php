<?php

namespace PragaonjTest\Validator;

use Pragaonj\Validator\PasswordValidator;
use PHPUnit\Framework\TestCase;

class PasswordValidatorTest extends TestCase
{
    /**
     * @param array $options
     * @param string $password
     * @param array $messages
     * @return void
     * @dataProvider insecurePasswordDataProvider
     */
    public function testValidationFailsOnInsecurePasswords(array $options, string $password, array $messages)
    {
        $validator = new PasswordValidator($options);

        $this->assertFalse($validator->isValid($password));
        $this->assertEquals($messages, array_keys($validator->getMessages()));
    }

    public function insecurePasswordDataProvider(): array
    {
        return [
            [
                [
                    "characterSets" => [
                        PasswordValidator::DIGIT
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "foo",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::CAPITAL_LETTER
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "foo",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::LETTER
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "123",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "foo",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                        PasswordValidator::CAPITAL_LETTER,
                        PasswordValidator::DIGIT,
                    ],
                    "numberOfRequiredCharacterSets" => 4
                ],
                "foo",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                        PasswordValidator::CAPITAL_LETTER,
                        PasswordValidator::DIGIT,
                    ],
                    "numberOfRequiredCharacterSets" => 4
                ],
                "fooBar123",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                        PasswordValidator::CAPITAL_LETTER,
                        PasswordValidator::DIGIT,
                    ],
                    "numberOfRequiredCharacterSets" => 3
                ],
                "fooBar",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                        PasswordValidator::CAPITAL_LETTER,
                        PasswordValidator::DIGIT,
                    ],
                    "numberOfRequiredCharacterSets" => 3
                ],
                "123!",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                    ],
                    "numberOfRequiredCharacterSets" => 2
                ],
                "123!!!!?BAR",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
        ];
    }

    /**
     * @param array $options
     * @param string $password
     * @return void
     * @dataProvider securePasswordDataProvider
     */
    public function testValidationPassesOnSecurePasswords(array $options, string $password)
    {
        $validator = new PasswordValidator($options);

        $this->assertTrue($validator->isValid($password));
    }

    public function securePasswordDataProvider(): array
    {
        return [
            [
                [
                    "characterSets" => [
                        PasswordValidator::DIGIT
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "1",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::CAPITAL_LETTER
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "A",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::LETTER
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "f",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER
                    ],
                    "numberOfRequiredCharacterSets" => 1
                ],
                "!",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                        PasswordValidator::CAPITAL_LETTER,
                        PasswordValidator::DIGIT,
                    ],
                    "numberOfRequiredCharacterSets" => 4
                ],
                "fooBar!?123",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                        PasswordValidator::CAPITAL_LETTER,
                        PasswordValidator::DIGIT,
                    ],
                    "numberOfRequiredCharacterSets" => 3
                ],
                "fooBar123",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                        PasswordValidator::CAPITAL_LETTER,
                    ],
                    "numberOfRequiredCharacterSets" => 3
                ],
                "fooBar!",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
            [
                [
                    "characterSets" => [
                        PasswordValidator::SPECIAL_CHARACTER,
                        PasswordValidator::LETTER,
                    ],
                    "numberOfRequiredCharacterSets" => 2
                ],
                "123!!!!?BARfoo",
                [
                    "msgNotEnoughCharacterSets"
                ]
            ],
        ];
    }

    /**
     * @return void
     */
    public function testValidationFailsOnInsecurePasswordsWithCustomMessage()
    {
        $validator = new PasswordValidator([
            "characterSets" => [
                PasswordValidator::DIGIT
            ],
            "numberOfRequiredCharacterSets" => 1,
            "messageTemplates" => [
                PasswordValidator::MSG_NOTENOUGHCHARACTARSETS => "custom error message"
            ]
        ]);

        $this->assertFalse($validator->isValid("foo"));
        $this->assertEquals(["msgNotEnoughCharacterSets" => "custom error message"], $validator->getMessages());
    }
}