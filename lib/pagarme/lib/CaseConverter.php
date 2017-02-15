<?php

namespace PagarMe\Sdk;

trait CaseConverter
{
    use CaseConverter;

    /**
     * @param string $sentence
     * @return string
     */
    public function snakeToUpperCamel($sentence)
    {
        return preg_replace_callback("/(?:^|_)([a-zA-Z])/", function ($word) {
            return strtoupper($word[1]);
        }, $sentence);
    }

    /**
     * @param string $sentence
     * @return string
     */
    public function snakeToLowerCamel($sentence)
    {
        return lcfirst($this->snakeToUpperCamel($sentence));
    }
}
