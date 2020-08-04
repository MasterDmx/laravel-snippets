<?php

namespace MasterDmx\LaravelSnippets\Entities;

use Illuminate\Support\Str;

abstract class Snippet
{
    /**
     * ID
     *
     * @var string
     */
    protected $id;

    /**
     * Заменяемый тэг
     *
     * @var string|null
     */
    protected $tag;

    /**
     * Паттерн замены
     *
     * @var string
     */
    protected $pattern = 'single';

    /**
     * Параметры тега
     *
     * @var array
     */
    protected $options = [];

    /**
     * Контент внутри тега
     *
     * @var string
     */
    protected $slot;

    public function __construct($id)
    {
        $this->id = $id;

        if (!isset($this->tag)) {
            $this->tag = $this->id;
        }
    }

    /**
     * Результат замены
     */
    abstract public function result();

    /**
     * Замена тега на результат
     *
     * @param [type] $content
     * @return void
     */
    public function replace($content)
    {
        if (!$this->preprocess($content)) {
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

            $this->options = $options ?? [];
            $this->slot = $matches[2] ?? null;

            return $this->result();
        };

        return preg_replace_callback($this->getPattern(), $closure, $content);
    }

    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Получить паттерн для регулярки
     *
     * @return string
     */
    protected function getPattern(): string
    {
        if ($this->pattern == 'single') {
            return '|\{\{' . $this->tag . '(.*?)\}\}|';
        } elseif ($this->pattern == 'double') {
            return '|\[' . $this->tag . '(.*?)\](.*?)\[\/' . $this->tag . '\]|';
        }

        return $this->pattern;
    }

    /**
     * Предобработка перед регуляркой
     *
     * @param string $content
     * @return boolean
     */
    protected function preprocess($content): bool
    {
        if ($this->pattern == 'single') {
            return Str::contains($content, '{{' . $this->tag);
        }

        return true;
    }

    /**
     * Вывод шаблона
     *
     * @param string $template шаблон
     * @param array $extraData
     */
    protected function view(string $template, array $extraData = [])
    {
        $data = $this->options;
        $data['tag'] = $this->tag;
        $data['slot'] = $this->slot;

        foreach ($extraData ?? [] as $key => $value) {
            $data[$key] = $value;
        }

        return view($template, $data);
    }
}
