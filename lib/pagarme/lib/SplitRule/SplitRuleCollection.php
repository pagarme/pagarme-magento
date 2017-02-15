<?php

namespace PagarMe\Sdk\SplitRule;

class SplitRuleCollection implements \ArrayAccess, \Iterator
{

    /**
     * @var array $rules
     */
    private $rules = [];

    /**
     * @var int $position
     */
    private $position = 0;

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof SplitRule) {
            throw new \InvalidArgumentException(
                "The value supplied is not a SplitRule object",
                1
            );
        }

        if (is_null($offset)) {
            $this->rules[] = $value;
        } else {
            $this->rules[$offset] = $value;
        }
    }

    public function offsetExists($offset)
    {
        return isset($this->rules[$offset]);
    }

    public function offsetUnset($offset)
    {
        unset($this->rules[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->rules[$offset]) ? $this->rules[$offset] : null;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->rules[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->rules[$this->position]);
    }
}
