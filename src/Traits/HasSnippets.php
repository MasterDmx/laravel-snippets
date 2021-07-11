<?php

namespace MasterDmx\LaravelSnippets\Traits;

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
     * @param string|array|Collection $presets
     *
     * @return $this
     */
    protected function applySnippetsPresetsForAttributes(string|array|Collection $attributes, string|array|Collection $presets): static
    {
        if (is_string($attributes)){
            $attributes = explode(',', $attributes);
        }

        foreach ($attributes as $attribute){
            if (isset($this->$attribute)){
                $this->$attribute = $this->snippets()->applyPresetTo($this->$attribute, $presets);
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
    protected function applySnippetsForAttributes(string|array|Collection $attributes, string|array|Collection $snippets): static
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
