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

use Extly\Infrastructure\Support\HtmlAsset\Repository;

/**
 * @deprecated
 */
final class LinkStylesheetTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    public function __construct(string $href, array $attributes = [])
    {
        // Similar to LinkDeferStylesheetTag
        $defaultAttributes = [
            'position' => Repository::GLOBAL_POSITION_BODY,
        ];

        $script = LinkStylesheetByScript::renderScript($href);
        $noScriptTag = new LinkCriticalStylesheetTag($href);

        parent::__construct('script', $script, array_merge($defaultAttributes, $attributes), $noScriptTag);
    }
}
