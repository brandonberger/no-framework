<?php declare(strict_types = 1);

namespace Underground\Page;

interface PageReader
{
    public function readBySlug(string $slug) : string;
}
