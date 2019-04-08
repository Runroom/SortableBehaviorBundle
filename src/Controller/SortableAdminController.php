<?php

declare(strict_types=1);

/*
 * This file is part of the SortableBehaviorBundle.
 *
 * (c) Runroom <runroom@runroom.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Runroom\SortableBehaviorBundle\Controller;

use Runroom\SortableBehaviorBundle\Services\PositionHandler;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PropertyAccess\PropertyAccess;

final class SortableAdminController extends CRUDController
{
    private $translator;
    private $positionHandler;

    public function __construct(
        TranslatorInterface $translator,
        PositionHandler $positionHandler
    ) {
        $this->translator = $translator;
        $this->positionHandler = $positionHandler;
    }

    public function moveAction(string $position): Response
    {
        if (!$this->admin->isGranted('EDIT')) {
            $this->addFlash(
                'sonata_flash_error',
                $this->translator->trans('flash_error_no_rights_update_position')
            );

            return new RedirectResponse($this->admin->generateUrl(
                'list',
                ['filter' => $this->admin->getFilterParameters()]
            ));
        }

        $object = $this->admin->getSubject();

        $lastPositionNumber = $this->positionHandler->getLastPosition($object);
        $newPositionNumber = $this->positionHandler->getPosition($object, $position, $lastPositionNumber);

        $accessor = PropertyAccess::createPropertyAccessor();
        $accessor->setValue($object, $this->positionHandler->getPositionFieldByEntity($object), $newPositionNumber);

        $this->admin->update($object);

        if ($this->isXmlHttpRequest()) {
            return $this->renderJson([
                'result' => 'ok',
                'objectId' => $this->admin->getNormalizedIdentifier($object),
            ]);
        }

        $this->addFlash(
            'sonata_flash_success',
            $this->translator->trans('flash_success_position_updated')
        );

        return new RedirectResponse($this->admin->generateUrl(
            'list',
            ['filter' => $this->admin->getFilterParameters()]
        ));
    }
}
