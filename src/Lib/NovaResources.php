<?php

namespace Ferdiunal\NovaShield\Lib;

use App\Nova\Resource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class NovaResources
{
    /**
     * @param  array  $resourcesPath  Paths to Nova resource directories.
     * @param  array  $resources  List of registered resources with metadata.
     */
    public function __construct(
        public readonly array $resourcesPath = [],
        public array $resources = []
    ) {
        $this->sync();
    }

    /**
     * Synchronizes the resources by scanning the directories provided in $resourcesPath
     * and registering valid Nova resources.
     */
    public function sync(): void
    {
        foreach ($this->resourcesPath as $path) {
            if (is_array($path)) {
                $this->resources[] = $path;

                continue;
            } elseif ($this->hasSettingResource($path)) {
                $this->registerNovaSettingResource($path);

                continue;
            } elseif (is_dir($path)) {
                $files = File::files($path);
                foreach ($files as $file) {
                    $resource = Str::of($file->getPathname())->explode('.php')->first();
                    $this->registerResource($resource);
                }
            }
        }
    }

    /**
     * Checks if the given path contains a valid Nova resource class.
     *
     * @param  string  $path  Path to a potential resource class.
     * @return bool True if the class exists and is a subclass of Nova Resource.
     */
    public function hasResource(string $path): bool
    {
        return class_exists($path) && is_subclass_of($path, Resource::class) && property_exists($path, 'model');
    }

    /**
     * Checks if the given path contains a valid Nova setting resource class.
     */
    public function hasSettingResource(string $path): bool
    {
        return class_exists($path) && is_subclass_of($path, \Ferdiunal\NovaSettings\SettingResource::class);
    }

    /**
     * Registers a resource class by extracting its metadata, such as model name,
     * URI key, policies, and group, and adds it to the $resources array.
     *
     * @param  string  $file  The file path or resource name to be registered.
     */
    public function registerResource(string $file): void
    {
        $resource = $this->getNamespace($file);
        $this->registerNovaResource($resource);
        $this->registerNovaSettingResource($resource);
    }

    /**
     * Registers a Nova resource by extracting its metadata and adding it to the $resources array.
     */
    public function registerNovaResource(string $resource): void
    {
        if ($this->hasResource($resource)) {
            /** @var resource $resource */
            $model = class_basename($resource::$model);
            $prefix = str($resource::uriKey())->camel()->append('::');

            $this->resources[] = [
                'name' => $resource::label(),
                'prefix' => $prefix,
                'policies' => array_map(
                    fn ($policy) => str($policy)->replace('{Model}', $model)->prepend($prefix)->toString(),
                    config('nova-shield.policies', [])
                ),
            ];
        }
    }

    /**
     * Registers a Nova setting resource by extracting its metadata and adding it to the $resources array.
     */
    public function registerNovaSettingResource(string $resource): void
    {
        if ($this->hasSettingResource($resource)) {
            $prefix = str($resource::title())->camel()->append('::');
            $this->resources[] = [
                'name' => $resource::title(),
                'prefix' => $prefix->toString(),
                'policies' => [
                    $prefix->append('view')->toString(),
                ],
            ];
        }
    }

    /**
     * Converts a file path to the corresponding fully qualified namespace of the resource.
     *
     * @param  string  $novaPath  The file path to the Nova resource class.
     * @return string The fully qualified namespace of the resource class.
     */
    protected function getNamespace(
        string $novaPath
    ) {
        $path = preg_replace(
            [
                '/^('.preg_quote(base_path(), '/').')/',
                '/\//',
            ],
            [
                '',
                '\\',
            ],
            $novaPath
        );

        $namespace = implode('\\', array_map(fn ($directory) => ucfirst($directory), explode('\\', $path)));

        // Remove leading backslash if present
        if (substr($namespace, 0, 1) === '\\') {
            $namespace = substr($namespace, 1);
        }

        return $namespace;
    }
}
