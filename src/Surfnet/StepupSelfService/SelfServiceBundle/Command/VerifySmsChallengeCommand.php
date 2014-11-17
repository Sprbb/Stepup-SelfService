<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupSelfService\SelfServiceBundle\Command;

use Symfony\Component\Validator\Constraints as Assert;

class VerifySmsChallengeCommand
{
    /**
     * @Assert\Expression(
     *     expression="this.challenge == this.expectedChallenge",
     *     message="ss.ss_verify_sms_challenge_command.challenge.does_not_equal_expected_challenge"
     * )
     *
     * @var string
     */
    public $challenge;

    /**
     * @var string
     */
    public $expectedChallenge;
}
