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

final class LinkStylesheetByPreloadAsStyle extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    public const DEFAULT_ATTRIBUTES = [
        'rel' => 'preload',
        'as' => 'style',
        'onload' => 'this.onload=null;this.rel = "stylesheet"',
    ];

    public function __construct(string $href, array $attributes = [])
    {
        // Defer non-critical CSS - https://web.dev/defer-non-critical-css/
        // Firefox doesn't support it: https://caniuse.com/#feat=link-rel-preload
        $attributes['href'] = $href;
        $linkCriticalStylesheetTag = new LinkCriticalStylesheetTag($href);

        parent::__construct('link', '', array_merge(self::DEFAULT_ATTRIBUTES, $attributes), $linkCriticalStylesheetTag);
    }
}
