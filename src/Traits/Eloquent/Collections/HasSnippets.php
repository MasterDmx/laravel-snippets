<?php

namespace MasterDmx\LaravelSnippets\Traits\Eloquent\Collection;

trait HasSnippets
{
    /**
     * Применяет снипеты ко всем моделям коллекции
     *
     * @return $this
     */
    public function applySnippets(): static
    {
        $this->map(fn ($model) => $model->applySnippets());

        return $this;
    }
}
