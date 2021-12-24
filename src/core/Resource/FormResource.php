<?php

namespace Duxravel\Core\Resource;

use Duxravel\Core\Resource\BaseResource;

class FormResource extends BaseResource
{

    public function toArray($request): array
    {
        return [
            'name' => $this->name,
            'description' => $this->description,
            'audit' => $this->audit,
        ];
    }
}
