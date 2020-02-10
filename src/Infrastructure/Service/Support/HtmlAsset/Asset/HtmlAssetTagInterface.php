<?php

/*
 * @package     Extly Infrastructure Support
 *              Beyond the JDocument, the Asset Tags Builder manages
 *                the generation of script and style tags for an Html Document.
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2020 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace Extly\Infrastructure\Support\HtmlAsset\Asset;

use Illuminate\Support\Collection;

interface HtmlAssetTagInterface
{
    public function getPosition(): string;

    public function getPriority(): int;

    public function getTag(): string;

    public function getInnerHtml(): string;

    public function getAttributes(): Collection;
}
