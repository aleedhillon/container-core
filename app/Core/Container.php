<?php

namespace App\Core;

use App\Exceptions\ContainerException;
use App\Exceptions\NotFoundException;
use Psr\Container\ContainerInterface;
use ReflectionClass;
use ReflectionNamedType;
use ReflectionParameter;
use ReflectionUnionType;

class Container implements ContainerInterface
{
    private array $entries = [];
    private array $resolved = [];

    public function get(string $id)
    {
        if($this->isShared($id)) {
            return $this->getShared($id);
        }
        return $this->resolve($id);
    }

    public function has(string $id): bool
    {
        return isset($this->entries[$id]);
    }

    private function set(string $id, callable|string $concrete, bool $shared = false)
    {
        $this->entries[$id] = compact('concrete', 'shared');
    }

    private function isShared(string $id)
    {
        return $this->has($id) && $this->entries[$id]['shared'];
    }

    private function getShared(string $id)
    {
        if(!isset($this->resolved[$id])) {
            $this->resolved[$id] = $this->resolve($id);
        }

        return $this->resolved[$id];
    }

    public function bind(string $id, callable|string $concrete)
    {
        $this->set($id, $concrete, false);
    }

    public function singleton(string $id, callable|string $concrete)
    {
        $this->set($id, $concrete, true);
    }

    protected function resolve(string $id)
    {
        if ($this->has($id)) {
            $entry = $this->entries[$id]['concrete'];

            if (is_callable($entry)) {
                return $entry($this);
            }

            $id = $entry;
        }

        $reflectionClass = new ReflectionClass($id);

        if (!$reflectionClass->isInstantiable()) {
            throw new ContainerException("Class {$id} is not instantiable.");
        }

        $constructor = $reflectionClass->getConstructor();

        if (!$constructor) {
            return new $id;
        }

        $parameters = $constructor->getParameters();

        if (!$parameters) {
            return new $id;
        }

        $dependencies = array_map(function (ReflectionParameter $parameter) use ($id) {
            $name = $parameter->getName();
            $type = $parameter->getType();

            if (!$type) {
                throw new ContainerException("Failed to resolve class {$id} because {$name} parameter is missing type hint.");
            }

            if ($type instanceof ReflectionUnionType) {
                throw new ContainerException("Failed to resolve class {$id} because {$name} parameter is union type.");
            }

            if ($type instanceof ReflectionNamedType && !$type->isBuiltin()) {
                return $this->get($type->getName());
            }

            throw new ContainerException("Failed to resolve class {$id} because invalid parameter {$name}.");
        }, $parameters);

        return $reflectionClass->newInstanceArgs($dependencies);
    }
}
