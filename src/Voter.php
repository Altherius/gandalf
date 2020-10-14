<?php

namespace Gandalf;

interface Voter
{
    public function abstains(string $permission, object $object): bool;
    public function vote(string $permission, object $object): bool;
}