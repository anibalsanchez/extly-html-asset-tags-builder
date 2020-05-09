<?php

/*
 * @package     Extly Infrastructure Support
 *
 * @author      Extly, CB. <team@extly.com>
 * @copyright   Copyright (c)2012-2020 Extly, CB. All rights reserved.
 * @license     http://www.opensource.org/licenses/mit-license.html  MIT License
 *
 * @see         https://www.extly.com
 */

namespace Extly\Infrastructure\Support\HtmlAsset\Asset;

use Extly\Infrastructure\Creator\CreatorTrait;

final class LinkPreloadStylesheetTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    use CreatorTrait;

    // Defer non-critical CSS - https://web.dev/defer-non-critical-css/
    const DEFAULT_ATTRIBUTES = [
        'rel' => 'preload',
        'as' => 'style',
        'onload' => 'this.onload=null;this.rel = "stylesheet"',
    ];

    public function __construct(string $href, array $attributes = [])
    {
        $attributes['href'] = $href;
        $noScriptTag = LinkCriticalStylesheetTag::create($href);

        parent::__construct('link', '', array_merge(self::DEFAULT_ATTRIBUTES, $attributes), $noScriptTag);
    }
}
