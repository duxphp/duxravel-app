<?php

namespace Duxravel\Core\Resource;

use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Article\Resource\ArticleClassCollection;

class BaseResource extends JsonResource
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

    protected function filterFields($array): array
    {
        if (!$this->hide) {
            return collect($array)->only($this->withoutFields)->toArray();
        }
        return collect($array)->except($this->withoutFields)->toArray();
    }

    public function resolve($request = null)
    {
        $visibleFieldsArray = parent::resolve($request);
        return $this->filterFields($visibleFieldsArray);
    }

}
