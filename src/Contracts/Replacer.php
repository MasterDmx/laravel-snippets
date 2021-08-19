<?php

namespace MasterDmx\LaravelSnippets\Contracts;

interface Replacer
{
    public function replace(string $data): string;
}
