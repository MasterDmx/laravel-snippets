## Установка

1. Скачивание плагина
```
composer require masterdmx/laravel-snippets
```

2. Подключение провайдера в config app.php раздел providers
```php
MasterDmx\LaravelSnippets\SnippetsServiceProvider::class
```

3. Публикация конфига (если планируется использовать группы)
```
php artisan vendor:publish --provider="MasterDmx\LaravelSnippets\SnippetsServiceProvider" --tag="config"
```

## Использование

### Регистрация сниппетов для использования пресетов в конфиге `snippets.php`

```php
return [
    'presets' => [
        'global' => [
            \App\View\Snippets\CurrentYear::class,
        ],
        
        'tinymce' => [
            \App\View\Snippets\TableOfContents::class,
        ],
    ],
];
```

### Подготовка модели

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use MasterDmx\LaravelSnippets\Traits\HasSnippets;
use MasterDmx\LaravelSnippets\SnippetsConfigurator;
use App\View\Snippets\GeoReplacer;

/**
 * @property string $title
 * @property string $annotation
 * @property string $content
 */
class Post extends Model
{
    use HasSnippets;

    public function snippetsSettings(SnippetsConfigurator $snippets): void
    {
        $snippets->bind(\App\View\Snippets\CurrentYear::class, 'title');
        $snippets->bind(\App\View\Snippets\CurrentDay::class, ['title', 'meta_title']);
        $snippets->bindPreset('global', 'meta_title');
        $snippets->bindReplacer(\App\View\Snippets\GeoReplacer::class, 'title');
    }
}
```
