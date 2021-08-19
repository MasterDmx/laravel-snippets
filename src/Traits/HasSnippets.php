<?php

namespace MasterDmx\LaravelSnippets\Traits;

use Illuminate\Support\Collection;
use MasterDmx\LaravelSnippets\Contracts\Replacer;
use MasterDmx\LaravelSnippets\Exceptions\MissSettingsForReplacerException;
use MasterDmx\LaravelSnippets\Snippets;
use MasterDmx\LaravelSnippets\SnippetsConfigurator;
use function app;

trait HasSnippets
{
    protected SnippetsConfigurator $snippetsConfigurator;

    abstract protected function snippetsSettings(SnippetsConfigurator $snippets);

    /**
     * Применяет все сниппеты
     *
     * @return $this
     */
    public function applySnippets(array|Replacer|null $replacers = null): static
    {
        $this->initConfiguration();

        // Сниппеты
        if(isset($this->snippetsConfigurator->snippets)) {
            foreach ($this->snippetsConfigurator->snippets as $snippet => $attributes){
                foreach ($attributes as $attribute) {
                    if (isset($this->$attribute)) {
                        $this->$attribute = $this->snippets()->applyTo($this->$attribute, $snippet);
                    }
                }
            }
        }

        // Пресеты
        if(isset($this->snippetsConfigurator->presets)) {
            foreach ($this->snippetsConfigurator->presets as $preset => $attributes){
                foreach ($attributes as $attribute) {
                    if (isset($this->$attribute)) {
                        $this->$attribute = $this->snippets()->applyPresetTo($this->$attribute, $preset);
                    }
                }
            }
        }

        // Реплейсеры
        if (isset($replacers)) {
            $this->applyReplacer($replacers);
        }

        return $this;
    }

    /**
     * Применяет реплейсеры
     *
     * @param array|Replacer $replacers
     */
    public function applyReplacer(array|Replacer $replacers)
    {
        $this->initConfiguration();

        if (!is_array($replacers)) {
            $replacers = [$replacers];
        }

        foreach ($replacers as $replacer) {
            if (!isset($this->snippetsConfigurator->replacers[$replacer::class])) {
                throw new MissSettingsForReplacerException('Settings for ' . $replacer::class . ' is missing');
            }

            foreach ($this->snippetsConfigurator->replacers[$replacer::class] as $attribute){
                if (isset($this->$attribute)) {
                    $this->$attribute = $this->snippets()->applyReplacerTo($this->$attribute, $replacer);
                }
            }
        }
    }

    /**
     * Инициализирует настройки сниппетов
     */
    protected function initConfiguration(): void
    {
        if (!isset($this->snippetsConfigurator)) {
            $configurator = app(SnippetsConfigurator::class);
            $this->snippetsSettings($configurator);
            $this->snippetsConfigurator = $configurator;
        }
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
