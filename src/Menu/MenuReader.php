<?php declare(strict_types = 1);

namespace Underground\Menu;

interface MenuReader
{
    public function readMenu() : array;
}
