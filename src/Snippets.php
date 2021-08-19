<?php

namespace MasterDmx\LaravelSnippets;

use Illuminate\Support\Collection;
use MasterDmx\LaravelSnippets\Contracts\Replacer;
use MasterDmx\LaravelSnippets\Exceptions\InvalidSnippetException;
use MasterDmx\LaravelSnippets\Exceptions\SnippetNotFoundException;
use MasterDmx\LaravelSnippets\Exceptions\UndefinedPresetAliasException;
use MasterDmx\LaravelSnippets\Exceptions\UndefinedSnippetAliasException;
use MasterDmx\LaravelSnippets\Contracts\Snippet;

/**
 * Управляющий сниппетами
 *
 * @package MasterDmx\LaravelTextSnippets
 */
class Snippets
{
    /**
     * Сниппеты с синглтона
     *
     * @var Snippet[]
     */
    private array $singleton = [];

    /**
     * Пресеты
     *
     * @var Snippet[]
     */
    private array $presets = [];

    /**
     * Возвращает объект сниппета
     */
    public function get(string $class): Snippet
    {
        if (isset($this->singleton[$class])){
            return $this->singleton[$class];
        }

        /** @var Snippet $snippet */
        $snippet = app($class);

        if (!is_a($snippet, Snippet::class)) {
            throw new InvalidSnippetException('Snippet {$class} must implement Snippet contract');
        }

        if ($snippet->isSingleton()){
            return $this->singleton[$class] = $snippet;
        }

        return $snippet;
    }

    /**
     * Применяет сниппеты из $snippets к тексту $text и возвращает измененный текст
     *
     * @param string                  $text
     * @param string|array|Collection $snippets
     *
     * @return string
     */
    public function applyTo(string $text, string|array|Collection $snippets): string
    {
        if (is_string($snippets)) {
            $snippets = [$snippets];
        }

        foreach ($snippets as $class){
            $text = $this->get($class)->replace($text);
        }

        return $text;
    }

    /**
     * Применяет сниппеты из групп $groups к тексту $text и возвращает измененный текст
     *
     * @param string                  $text
     * @param string|array|Collection $groups
     *
     * @return string
     */
    public function applyPresetTo(string $text, string|array|Collection $groups): string
    {
        $snippets = [];

        if (is_string($groups)) {
            $groups = [$groups];
        }

        foreach ($groups as $group){
            if (!empty($this->presets[$group])){
                foreach ($this->presets[$group] as $class){
                    $snippets[$class] = $class;
                }
            }
        }

        return !empty($snippets) ? $this->applyTo($text, $snippets) : $text;
    }

    /**
     * Применяет реплейсеры из $snippets к тексту $text и возвращает измененный текст
     *
     * @param string                  $text
     * @param Replacer $replacer
     *
     * @return string
     */
    public function applyReplacerTo(string $text, Replacer $replacer): string
    {
        return $replacer->replace($text);
    }

    /**
     * Добавляет группу со сниппетами
     *
     * @param string $name
     * @param array  $snippets
     */
    public function addPreset(string $name, array $snippets)
    {
        $this->presets[$name] = $snippets;
    }
}
