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

namespace Surfnet\StepupSelfService\SelfServiceBundle\Controller;

use Psr\Log\LoggerInterface;
use Surfnet\StepupBundle\Command\SwitchLocaleCommand;
use Surfnet\StepupBundle\Form\Type\SwitchLocaleType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

final class LocaleController extends Controller
{
    public function switchLocaleAction(Request $request)
    {
        $returnUrl = $request->query->get('return-url');

        // Return URLs generated by us always include a path (ie. at least a forward slash)
        // @see https://github.com/symfony/symfony/blob/master/src/Symfony/Component/HttpFoundation/Request.php#L878
        $domain = $request->getSchemeAndHttpHost() . '/';
        if (strpos($returnUrl, $domain) !== 0) {
            $this->get('logger')->error(sprintf(
                'Identity "%s" used illegal return-url for redirection after changing locale, aborting request',
                $this->getIdentity()->id
            ));

            throw new BadRequestHttpException('Invalid return-url given');
        }

        /** @var LoggerInterface $logger */
        $logger = $this->get('logger');
        $logger->info('Switching locale...');

        $identity = $this->getIdentity();
        if (!$identity) {
            throw new AccessDeniedHttpException('Cannot switch locales when not authenticated');
        }

        $command = new SwitchLocaleCommand();
        $command->identityId = $identity->id;

        $form = $this->createForm(
            SwitchLocaleType::class,
            $command,
            ['route' => 'ss_switch_locale', 'route_parameters' => ['return_url' => $returnUrl]]
        );
        $form->handleRequest($request);

        if (!$form->isValid()) {
            $this->addFlash('error', $this->get('translator')->trans('ss.flash.invalid_switch_locale_form'));
            $logger->error('The switch locale form unexpectedly contained invalid data');
            return $this->redirect($returnUrl);
        }

        $service = $this->get('self_service.service.identity');
        if (!$service->switchLocale($command)) {
            $this->addFlash('error', $this->get('translator')->trans('ss.flash.error_while_switching_locale'));
            $logger->error('An error occurred while switching locales');
            return $this->redirect($returnUrl);
        }

        $logger->info('Successfully switched locale');

        return $this->redirect($returnUrl);
    }
}
