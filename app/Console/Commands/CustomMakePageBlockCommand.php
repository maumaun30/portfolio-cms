<?php

namespace App\Console\Commands;

use Filament\Support\Commands\Concerns\CanManipulateFiles;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use function Laravel\Prompts\text;

class CustomMakePageBlockCommand extends Command
{
    use CanManipulateFiles;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'custom-fabricator:block {name?} {--F|force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a custom page block for filament-fabricator';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $pageBlock = (string) Str::of($this->argument('name') ?? text(
            label: 'What is the block name?',
            placeholder: 'HeroBlock',
            required: true,
        ))
            ->trim('/')
            ->trim('\\')
            ->trim(' ')
            ->replace('/', '\\');

        $pageBlockClass = (string) Str::of($pageBlock)->afterLast('\\');

        $pageBlockNamespace = Str::of($pageBlock)->contains('\\') ?
            (string) Str::of($pageBlock)->beforeLast('\\') :
            '';

        $shortName = Str::of($pageBlock)
            ->beforeLast('Block')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $view = Str::of($pageBlock)
            ->beforeLast('Block')
            ->prepend('components\\filament-fabricator\\page-blocks\\')
            ->explode('\\')
            ->map(fn ($segment) => Str::kebab($segment))
            ->implode('.');

        $path = app_path(
            (string) Str::of($pageBlock)
                ->prepend('Filament\\Fabricator\\PageBlocks\\')
                ->replace('\\', '/')
                ->append('.php'),
        );

        $viewPath = resource_path(
            (string) Str::of($view)
                ->replace('.', '/')
                ->prepend('views/')
                ->append('.blade.php'),
        );

        $files = [$path, $viewPath];

        if (! $this->option('force') && $this->checkForCollision($files)) {
            return static::INVALID;
        }

        $this->copyStubToApp('CustomPageBlock', $path, [
            'class' => $pageBlockClass,
            'namespace' => 'App\\Filament\\Fabricator\\PageBlocks' . ($pageBlockNamespace !== '' ? "\\{$pageBlockNamespace}" : ''),
            'shortName' => $shortName,
        ]);

        $this->copyStubToApp('PageBlockView', $viewPath);

        $this->info("Successfully created {$pageBlock}!");

        return static::SUCCESS;
    }
}
