<?php declare(strict_types = 1);

namespace Underground\Template;

interface Renderer
{
    public function render($template, $data = []) : string;
}
