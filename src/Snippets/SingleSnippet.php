<?php

namespace MasterDmx\LaravelSnippets\Snippets;

use Illuminate\Support\Str;
use MasterDmx\LaravelSnippets\Contracts\Snippet;

abstract class SingleSnippet implements Snippet
{
    /**
     * Результат замены
     *
     * @param array|null $options
     *
     * @return string
     */
    abstract protected function result(array $options = null): string;

    /**
     * Название тега
     *
     * @return string
     */
    abstract protected function tag(): string;

    /**
     * Замена тега на результат
     *
     * @param string $content
     *
     * @return string
     */
    public function replace(string $content): string
    {
        if (!$this->isDetected($content)) {
            return $content;
        }

        $closure = function ($matches) {
            $matches[1] = $matches[1] ?? '';

            if (!empty($matches[1])) {
                parse_str(str_replace(' :', '&', preg_replace("/ {2,}/", " ", $matches[1])), $options);

                foreach ($options ?? [] as $key => $value) {
                    $options[$key] = trim($value);
                }
            }

            return $this->result($options ?? []);
        };

        return preg_replace_callback($this->pattern(), $closure, $content);
    }

    /**
     * Получить паттерн замены
     *
     * @return string
     */
    protected function pattern(): string
    {
        return '|\{' . $this->tag() . '(.*?)\}|';
    }

    /**
     * Проверка на присутствие тега в тексте
     *
     * @param string $content
     *
     * @return bool
     */
    protected function isDetected(string $content): bool
    {
        return Str::contains($content, '{' . $this->tag());
    }

    public function isSingleton(): bool
    {
        return false;
    }
}
