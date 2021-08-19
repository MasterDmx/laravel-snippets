<?php

namespace MasterDmx\LaravelSnippets;

use Illuminate\Support\Collection;

class SnippetsConfigurator
{
    public array $snippets = [];
    public array $presets = [];
    public array $replacers = [];

    /**
     * Связывает один или несколько публичных аттрибутов с одним или несколькими сниппетами
     */
    public function bind(string|array|Collection $snippets, string|array|Collection $attributes): void
    {
        foreach ($this->getArray($attributes) as $attribute){
            foreach ($this->getArray($snippets) as $snippet) {
                $this->snippets[$snippet][$attribute] = $attribute;
            }
        }
    }

    /**
     * Связывает один или несколько публичных аттрибутов с одним или несколькими сниппетами
     */
    public function bindPreset(string|array|Collection $presets, string|array|Collection $attributes): void
    {
        foreach ($this->getArray($attributes) as $attribute){
            foreach ($this->getArray($presets) as $preset) {
                $this->presets[$preset][$attribute] = $attribute;
            }
        }
    }

    /**
     * Связывает один или несколько публичных аттрибутов с одним или несколькими реплейсерами
     */
    public function bindReplacer(string|array|Collection $replacers, string|array|Collection $attributes): void
    {
        foreach ($this->getArray($attributes) as $attribute){
            foreach ($this->getArray($replacers) as $replacer) {
                $this->replacers[$replacer][$attribute] = $attribute;
            }
        }
    }

    private function getArray(string|array|Collection $items): array
    {
        if (is_string($items)){
            return explode(',', $items);
        }

        if (is_a($items, Collection::class)) {
            return $items->toArray();
        }

        return $items;
    }
}
