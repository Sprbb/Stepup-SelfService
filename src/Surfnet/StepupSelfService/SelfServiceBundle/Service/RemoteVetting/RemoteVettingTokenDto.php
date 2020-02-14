<?php
/**
 * Copyright 2010 SURFnet B.V.
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

namespace Surfnet\StepupSelfService\SelfServiceBundle\Service\RemoteVetting;

class RemoteVettingTokenDto
{
    /**
     * @var string
     */
    private $identityId;
    /**
     * @var string
     */
    private $secondFactorId;
    /**
     * @var string
     */
    private $requestId;

    /**
     * @param string $identityId
     * @param string $secondFactorId
     */
    public function __construct($identityId, $secondFactorId)
    {
        $this->identityId = $identityId;
        $this->secondFactorId = $secondFactorId;
    }

    public function isEqual(RemoteVettingTokenDto $token)
    {
        return $this == $token;
    }

    /**
     * @return string
     */
    public function getRequestId()
    {
        return $this->requestId;
    }

    /**
     * @param string $requestId
     */
    public function setRequestId($requestId)
    {
        $this->requestId = $requestId;
    }
}