<?php

namespace Duxravel\Core\Resource;

use Illuminate\Container\Container;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Resources\Json\ResourceCollection;
use JsonSerializable;

class BaseCollection extends ResourceCollection
{
    protected array $withoutFields = [];
    private bool $hide = true;
    protected string $type = 'default';

    public function type(string $request): self
    {
        $this->type = $request;
        return $this;
    }

    public function hide(array $fields): self
    {
        $this->withoutFields = $fields;
        return $this;
    }

    public function show(array $fields): self
    {
        $this->withoutFields = $fields;
        $this->hide = false;
        return $this;
    }

    public function resolve($request = null)
    {

        $request = $request ?: Container::getInstance()->make('request');

        $this->collection = $this->collection->map(function ($resource) use ($request) {
            $class = $this->collects();
            if (!$this->hide) {
                return (new $class($resource))->show($this->withoutFields);
            }
            return (new $class($resource))->hide($this->withoutFields);
        });

        $data = $this->toArray($request);

        if ($data instanceof Arrayable) {
            $data = $data->toArray();
        } elseif ($data instanceof JsonSerializable) {
            $data = $data->jsonSerialize();
        }
        return $this->filter((array) $data);
    }
}
