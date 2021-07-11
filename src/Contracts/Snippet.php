<?php

namespace MasterDmx\LaravelSnippets\Contracts;

interface Snippet
{
    public function replace(string $content): string;

    public function isSingleton(): bool;
}
