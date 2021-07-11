<?php

namespace MasterDmx\LaravelSnippets\Traits\Eloquent;

use Illuminate\Support\Collection;
use MasterDmx\LaravelSnippets\Snippets;
use function app;

trait HasSnippets
{
    abstract public function applySnippets(): static;

    /**
     * Применяет группы сниппетов для одного или нескольких аттрибутов модели
     *
     * @param string|array|Collection $attributes
     * @param string|array|Collection $groups
     *
     * @return $this
     */
    protected function applySnippetsGroupFor(string|array|Collection $attributes, string|array|Collection $groups): static
    {
        if (is_string($attributes)){
            $attributes = explode(',', $attributes);
        }

        foreach ($attributes as $attribute){
            if (isset($this->$attribute)){
                $this->$attribute = $this->snippets()->applyGroupTo($this->$attribute, $groups);
            }
        }

        return $this;
    }

    /**
     * Применяет один или несколько сниппетов для одного или нескольких аттрибутов модели
     *
     * @param string|array|Collection $attributes
     * @param string|array|Collection $snippets
     *
     * @return $this
     */
    protected function applySnippetsFor(string|array|Collection $attributes, string|array|Collection $snippets): static
    {
        if (is_string($attributes)){
            $attributes = explode(',', $attributes);
        }

        foreach ($attributes as $attribute){
            if (isset($this->$attribute)){
                $this->$attribute = $this->snippets()->applyTo($this->$attribute, $snippets);
            }
        }

        return $this;
    }

    /**
     * Возвращает глобальный менеджер сниппетов
     *
     * @return Snippets
     */
    protected function snippets(): Snippets
    {
        return app(Snippets::class);
    }
}
