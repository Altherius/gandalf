<?php

namespace Gandalf\Tests;

use Gandalf\DecisionManager;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class DecisionManagerTest extends TestCase
{
    public function testInstanciationWithoutArgument(): void
    {
        $decisionManager = new DecisionManager();
        self::assertSame($decisionManager->getStrategy(), DecisionManager::STRATEGY_AFFIRMATIVE);
    }

    public function testInstanciationWithCorrectArgument(): void
    {
        $decisionManager = new DecisionManager(DecisionManager::STRATEGY_CONSENSUS);
        self::assertSame($decisionManager->getStrategy(), DecisionManager::STRATEGY_CONSENSUS);
    }

    public function testInstanciationWithIncorrectArgument(): void
    {
        $this->expectException(InvalidArgumentException::class);
        new DecisionManager(null);
    }
}