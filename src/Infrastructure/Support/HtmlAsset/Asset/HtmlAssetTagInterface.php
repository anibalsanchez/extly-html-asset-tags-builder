<?php

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2024 Extly, CB. All rights reserved.
 * @license     https://www.opensource.org/licenses/mit-license.html  MIT License
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

    public function getNoScriptContentTag();
}
