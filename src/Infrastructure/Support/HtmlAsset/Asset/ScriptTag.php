<?php

/*
 * @package     Extly HTML Asset Tags Builder
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2021 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace Extly\Infrastructure\Support\HtmlAsset\Asset;

final class ScriptTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    public function __construct(string $src, array $attributes = [])
    {
        $defaultAttributes = [
            'defer' => true,
        ];

        $attributes['src'] = $src;

        parent::__construct('script', '', array_merge($defaultAttributes, $attributes));
    }
}
