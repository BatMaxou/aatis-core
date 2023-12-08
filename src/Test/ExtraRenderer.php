<?php

namespace App\Test;

use Aatis\TemplateRenderer\Service\PhpRenderer;

class ExtraRenderer extends PhpRenderer
{
    public const EXTENSION = ExtraTemplateFileExtension::EXTRA;
}
