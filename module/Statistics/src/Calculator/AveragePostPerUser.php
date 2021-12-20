<?php

declare(strict_types=1);

namespace Statistics\Calculator;

use SocialPost\Dto\SocialPostTo;
use Statistics\Dto\StatisticsTo;

class AveragePostPerUser extends AbstractCalculator
{
    protected const UNITS = 'posts';
    /**
     * @var array
     */
    private array $users = [];

    /**
     * @var int
     */
    private int $postCount = 0;

    /**
     * @inheritDoc
     */
    protected function doAccumulate(SocialPostTo $postTo): void
    {
        $this->postCount++;
        $this->users[$postTo->getAuthorId()] = $postTo->getAuthorId();
    }

    /**
     * @inheritDoc
     */
    protected function doCalculate(): StatisticsTo
    {
        $value = count($this->users) !== 0 ? $this->postCount / count($this->users) : 0;
        return (new StatisticsTo())->setValue(round($value, 2));
    }
}
