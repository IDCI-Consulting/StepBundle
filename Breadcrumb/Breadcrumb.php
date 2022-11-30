<?php

namespace IDCI\Bundle\StepBundle\Breadcrumb;

use IDCI\Bundle\StepBundle\Exception\InvalidDestinationException;
use IDCI\Bundle\StepBundle\Navigation\NavigatorInterface;
use Symfony\Component\HttpFoundation\Request;

class Breadcrumb
{
    public const BACK_QUERY_PARAMETER_ITEM_NAME = '_back_step';

    /**
     * @var Request
     */
    private $request;

    /**
     * @var NavigatorInterface
     */
    private $navigator;

    private array $items = [];

    /**
     * Constructor.
     */
    public function __construct(Request $request, NavigatorInterface $navigator)
    {
        $this->request = $request;
        $this->navigator = $navigator;
    }

    /**
     * Add an item in the breadcrumb.
     */
    public function addItem(string $itemName, array $stepNames): self
    {
        $this->items[$itemName] = $stepNames;

        return $this;
    }

    /**
     * Return all the items names in the breadcrumb.
     */
    public function getItemNames(): array
    {
        return array_keys($this->items);
    }

    /**
     * Return an item by the given item name.
     */
    public function getItem(string $itemName): ?array
    {
        return $this->items[$itemName];
    }

    /**
     * Return all the items of the breadcrumb.
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * Return the current item name of the breadcrumb.
     */
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

    /**
     * Return the go back step name by the parameter given in the request.
     */
    public function getGoBackStepName(): string
    {
        $itemName = $this->request->query->get(self::BACK_QUERY_PARAMETER_ITEM_NAME);
        $takenPathSource = $this->getTakenPathSource($itemName);

        if (null !== $takenPathSource) {
            return $takenPathSource;
        }

        throw new InvalidDestinationException($this->navigator->getCurrentStep()->getName(), sprintf('BreadcrumbItem::%s', $itemName));
    }

    /**
     * Return if the item is accessible.
     */
    public function isAccessibleItem(string $itemName): bool
    {
        return null !== $this->getTakenPathSource($itemName);
    }

    /**
     * Return a step name if possible by the given item name.
     */
    private function getTakenPathSource(string $itemName): ?string
    {
        foreach ($this->navigator->getFlow()->getHistory()->getTakenPaths() as $takenPath) {
            if (isset($takenPath['source']) && in_array($takenPath['source'], $this->getItem($itemName))) {
                return $takenPath['source'];
            }
        }

        return null;
    }

    /**
     * Return if the request has requested to go back.
     */
    public function hasRequestedGoBack(): bool
    {
        return $this->request->query->has(self::BACK_QUERY_PARAMETER_ITEM_NAME);
    }
}
