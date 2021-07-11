<?php

namespace MasterDmx\LaravelSnippets\Traits\Collections;

trait HasSnippets
{
    /**
     * Применяет снипеты ко всем моделям коллекции
     *
     * @return $this
     */
    public function applySnippets(): static
    {
        return $this->map(fn ($model) => $model->applySnippets());
    }
}
