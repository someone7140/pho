<?php

namespace App\Http\Response\Common;

class CommonAbstractResponse implements \JsonSerializable
{
    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }
}
