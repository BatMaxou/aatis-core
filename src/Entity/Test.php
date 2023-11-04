<?php

namespace App\Entity;

class Test
{
    private string $name;

    public function __construct()
    {
        $this->name = 'test';
    }

    public function print(): string
    {
        return 'Aatis ??';
    }
}
