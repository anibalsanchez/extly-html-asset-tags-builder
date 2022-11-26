<?php

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2022 Extly, CB. All rights reserved.
 * @license     https://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace Extly\Infrastructure\Support\HtmlAsset\Asset;

final class InlineScriptTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    public function __construct(string $innerHtml, array $attributes = [])
    {
        parent::__construct('script', $innerHtml, $attributes);
    }
}
