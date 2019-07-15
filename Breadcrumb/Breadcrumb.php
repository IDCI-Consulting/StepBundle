<?php

namespace IDCI\Bundle\StepBundle\Breadcrumb;

use IDCI\Bundle\StepBundle\Exception\InvalidDestinationException;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Symfony\Component\HttpFoundation\Request;

class Breadcrumb
{
    const BACK_QUERY_PARAMETER_ITEM_NAME = '_back_step';

    private $request;

    private $navigator;

    private $items = [];

    public function __construct(Request $request, NavigatorInterface $navigator)
    {
        $this->request = $request;
        $this->navigator = $navigator;
    }

    public function addItem(string $itemName, array $stepNames): self
    {
        $this->items[$itemName] = $stepNames;

        return $this;
    }

    public function getItemNames(): array
    {
        return array_keys($this->items);
    }

    public function getItem(string $itemName): ?array
    {
        return $this->items[$itemName];
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function getCurrentItem(): ?string
    {
        $currentStepName = $this->navigator->getCurrentStep()->getName();

        foreach ($this->items as $name => $stepNames) {
            if (in_array($currentStepName, $stepNames)) {
                return $name;
            }
        }

        return null;
    }

    public function getGoBackStepName()
    {
        $itemName = $this->request->query->get(self::BACK_QUERY_PARAMETER_ITEM_NAME);
        $takenPathSource = $this->getTakenPathSource($itemName);

        if (null !== $takenPathSource) {
            return $takenPathSource;
        }

        throw new InvalidDestinationException(
            $this->navigator->getCurrentStep()->getName(),
            sprintf('BreadcrumbItem::%s', $itemName)
        );
    }

    public function isAccessibleItem(string $itemName)
    {
        return null !== $this->getTakenPathSource($itemName);
    }

    private function getTakenPathSource(string $itemName)
    {
        foreach ($this->navigator->getFlow()->getHistory()->getTakenPaths() as $takenPath) {
            if (isset($takenPath['source']) && in_array($takenPath['source'], $this->getItem($itemName))) {
                return $takenPath['source'];
            }
        }

        return null;
    }

    public function hasRequestedGoBack(): bool
    {
        return $this->request->query->has(self::BACK_QUERY_PARAMETER_ITEM_NAME);
    }
}
