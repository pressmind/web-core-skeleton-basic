<?php


namespace Custom\REST\Controller;


class Example
{
    public function index($parameters)
    {
        return ['controller' => self::class , 'action' => 'index', 'parameters' => $parameters];
    }
}
