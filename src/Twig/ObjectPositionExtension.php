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

namespace Runroom\SortableBehaviorBundle\Twig;

use Runroom\SortableBehaviorBundle\Services\AbstractPositionHandler;
use Twig\Extension\AbstractExtension;

class ObjectPositionExtension extends AbstractExtension
{
    public const NAME = 'sortableObjectPosition';

    private $positionHandler;

    public function __construct(AbstractPositionHandler $positionHandler)
    {
        $this->positionHandler = $positionHandler;
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('currentObjectPosition', [$this, 'currentPosition']),
            new TwigFunction('lastPosition', [$this, 'lastPosition']),
        ];
    }

    public function currentPosition($entity): int
    {
        return $this->positionHandler->getCurrentPosition($entity);
    }

    public function lastPosition($entity): int
    {
        return $this->positionHandler->getLastPosition($entity);
    }
}
