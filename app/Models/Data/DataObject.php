<?php declare(strict_types=1);

namespace App\Models\Data;

use App\Models\Validation\PropertyValidator;
use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use InvalidArgumentException;
use ReflectionClass;
use ReflectionProperty;

abstract class DataObject implements Arrayable
{
    /**
     * @param array<string, string|float|int|bool|null|\Illuminate\Support\Carbon> $properties
     */
    public function hydrate(array $properties): void
    {
        foreach ($properties as $property => $value) {
            $this->{$property} = $value;
        }
    }

    /**
     * @template T of string|float|int|bool|null|\Illuminate\Support\Carbon
     * @param string $key
     * @param T $value
     * @return T
     */
    protected function validateProperty(string $key, string|float|int|bool|null|Carbon $value): string|float|int|bool|null|Carbon
    {
        (new PropertyValidator)->validate(static::class, $key, $value);

        return $value;
    }

    /**
     * @return array<string, string|float|int|bool|null|\Illuminate\Support\Carbon>
     */
    public function toArray($includeUntouchedProperties = false): array
    {
        $reflection = new ReflectionClass(static::class);
        $properties = $reflection->getProperties(ReflectionProperty::IS_PUBLIC);
        $array      = [];

        foreach ($properties as $property) {
            if (! $property->isInitialized($this) && $includeUntouchedProperties === false) {
                continue;
            }
            $array[$property->getName()] = $property->isInitialized($this) ? $property->getValue($this) : null;
        }
        return $array;
    }

    public function onEloquent(Closure $callback, bool $newDataObjectFromResults = false): static
    {
        $model   = $this->toEloquent();
        $results = $callback($model);

        if ($newDataObjectFromResults === false) {
            return $this;
        }
        return $results instanceof $model
            ? $this->newFromEloquentModel($results)
            : new static;
    }

    private function newFromEloquentModel(Model $model): static
    {
        // get Eloquent data without casting any Carbon instances etc. to strings
        $instance = new static;
        foreach (array_keys($model->getAttributes()) as $property) {
            $instance->{$property} = $model[$property];
        }
        return $instance;
    }

    public function toEloquent(): Model
    {
        return new ($this->getEloquentClassName())($this->toArray());
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __get($key)
    {
        throw new InvalidArgumentException('Property: ' . $key . ' not available on class: ' . static::class);
    }

    /**
     * @throws InvalidArgumentException
     */
    public function __set($key, $value)
    {
        throw new InvalidArgumentException('Can\'t set property: ' . $key . ', since it\'s not defined on class: ' . static::class);
    }

    /**
     * @return class-string
     */
    abstract protected function getEloquentClassName(): string;
}
