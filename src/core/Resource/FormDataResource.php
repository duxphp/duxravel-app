<?php

namespace Duxravel\Core\Resource;

use Duxravel\Core\Resource\BaseResource;

class FormDataResource extends BaseResource
{

    public function toArray($request): array
    {
        return $this->data;
    }
}
