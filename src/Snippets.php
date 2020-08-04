<?php

namespace MasterDmx\LaravelSnippets;

use ErrorException;

class Snipets
{
    /**
     * Сниппеты
     *
     * @var array
     */
    private $snippets = [];

    /**
     * Пресеты
     *
     * @var array
     */
    private $presets = [];

    public function __construct()
    {
        $this->snippets = config('snippets.snippets', []);
        $this->presets = config('snippets.presets', []);
    }

    public function convert($content)
    {
        foreach ($this->snippets as $id => $class) {
            $snippet = $this->getInstance($class, $id);
            $content = $snippet->replace($content);
        }

        return $content;
    }

    public function getInstance($class, $id): object
    {
        if (!class_exists($class)) {
            throw new ErrorException('Snippet not found');
        }

        return new $class($id);
    }
}
