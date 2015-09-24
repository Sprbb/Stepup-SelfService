<?php

/**
 * Copyright 2015 SURFnet B.V.
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

namespace Surfnet\StepupSelfService\SelfServiceBundle\Service\U2f;

final class RegistrationRevocationResult
{
    /**
     * Registration was a success.
     */
    const STATUS_SUCCESS = 'SUCCESS';

    /**
     * Error while talking with API.
     */
    const STATUS_API_ERROR = 'API_ERROR';

    /**
     * No registration with the given key handle is known.
     */
    const STATUS_UNKNOWN_KEY_HANDLE = 'UNKNOWN_KEY_HANDLE';

    /**
     * @var string
     */
    private $status;

    /**
     * @param string $status
     * @return RegistrationRevocationResult
     */
    public static function status($status)
    {
        return new self($status);
    }

    /**
     * @return RegistrationRevocationResult
     */
    public static function apiError()
    {
        return new self(self::STATUS_API_ERROR);
    }

    private function __construct($status)
    {
        $this->status = $status;
    }

    /**
     * @return bool
     */
    public function wasSuccessful()
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * @return bool
     */
    public function wasKeyHandleUnknown()
    {
        return $this->status === self::STATUS_UNKNOWN_KEY_HANDLE;
    }

    public function wasApiError()
    {
        return $this->status === self::STATUS_API_ERROR;
    }
}
