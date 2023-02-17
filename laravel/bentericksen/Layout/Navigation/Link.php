<?php

namespace Bentericksen\Layout\Navigation;

class Link
{
    public $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return string
     * @throws \Throwable
     */
    public function render()
    {
        return view('shared.navigation.link')->with('data', $this->data)->render();
    }
}