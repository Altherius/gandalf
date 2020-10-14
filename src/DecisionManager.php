<?php

namespace Gandalf;

use InvalidArgumentException;
use ReflectionClass;

class DecisionManager
{
    public const STRATEGY_UNANIMOUS = 1;
    public const STRATEGY_CONSENSUS = 2;
    public const STRATEGY_AFFIRMATIVE = 3;

    private $strategy;

    public function __construct($strategy = self::STRATEGY_AFFIRMATIVE)
    {
        $this->setStrategy($strategy);
    }

    public function decide(string $permission, object $object): bool
    {
        $yeas = 0;
        $nays = 0;

        /**
         * @var Voter[] $voters
         */
        $voters = $this->getVoters();
        foreach ($voters as $voter)
        {
            if ($voter->abstains($permission, $object)) {
                continue;
            }

            $affirmativeVote = $voter->vote($permission, $object);
            if ($affirmativeVote) {
                if ($this->strategy === self::STRATEGY_AFFIRMATIVE) {
                    return true;
                }
                ++$yeas;
            }

            else {
                if ($this->strategy === self::STRATEGY_UNANIMOUS) {
                    return false;
                }
                ++$nays;
            }
        }

        return ($yeas > $nays);
    }

    private function getVoters(): array
    {
        $classes = get_declared_classes();
        $voters = [];
        foreach ($classes as $class)
        {
            $reflection = new ReflectionClass($class);
            if ($reflection->implementsInterface(Voter::class)) {
                $voters[] = $class;
            }
        }

        return $voters;
    }

    /**
     * @return int
     */
    public function getStrategy(): int
    {
        return $this->strategy;
    }

    /**
     * @param mixed $strategy
     * @return DecisionManager
     */
    public function setStrategy($strategy): self
    {
        if (!in_array($strategy, [self::STRATEGY_UNANIMOUS, self::STRATEGY_CONSENSUS, self::STRATEGY_AFFIRMATIVE], true)) {
            throw new InvalidArgumentException("Invalid strategy given.");
        }
        $this->strategy = $strategy;
        return $this;
    }
}