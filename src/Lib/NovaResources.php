<?php

namespace Ferdiunal\NovaShield\Lib;

use App\Nova\Resource;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

final class NovaResources
{
    public function __construct(
        public readonly array $resourcesPath = [],
        public array $resources = []
    ) {
        $this->sync();
    }

    public function sync(): void
    {
        foreach ($this->resourcesPath as $path) {
            $files = File::files($path);
            foreach ($files as $file) {
                $resource = Str::of($file->getPathname())->explode(".php")->first();
                $namespace = $this->getNamespace($resource);

                if (class_exists($namespace) && is_subclass_of($namespace, Resource::class)) {
                    /** @var Resource $namespace */
                    $model = class_basename($namespace::$model);
                    $prefix = str($namespace::uriKey())->camel()->append("::");
                    $policies = array_map(
                        fn($policy) => str($policy)->replace("{Model}", $model)->prepend($prefix)->toString(),
                        config('nova-shield.policies', [])
                    );
                    $this->resources[] = [
                        'name' => $namespace::label(),
                        'uriKey' => $namespace::uriKey(),
                        'prefix' => $prefix->toString(),
                        'group' => $namespace::group(),
                        'namespace' => $namespace,
                        'policies' => $policies
                    ];
                }
            }
        }
    }

    protected function getNamespace(
        string $novaPath
    ) {
        $path = preg_replace(
            [
                '/^(' . preg_quote(base_path(), '/') . ')/',
                '/\//',
            ],
            [
                '',
                '\\',
            ],
            $novaPath
        );

        $namespace = implode('\\', array_map(fn($directory) => ucfirst($directory), explode('\\', $path)));

        // Remove leading backslash if present
        if (substr($namespace, 0, 1) === '\\') {
            $namespace = substr($namespace, 1);
        }

        return $namespace;
    }
}
