<?php

namespace App\Test;

use App\Test\ExtraTemplateFileExtension;
use Aatis\TemplateRenderer\Service\PhpRenderer;

class ZebiRenderer extends PhpRenderer
{
    public const EXTENSION = ExtraTemplateFileExtension::ZEBI;
}
