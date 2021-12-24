<?php

namespace Duxravel\Core\Resource;

use Duxravel\Core\Resource\BaseCollection;

class FormDataCollection extends BaseCollection
{

    public function toArray($request)
    {
        return $this->collection;
    }

}
