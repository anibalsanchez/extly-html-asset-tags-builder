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

final class LinkStylesheetByMediaPrint extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    use CreatorTrait;

    const DEFAULT_ATTRIBUTES = [
        'rel' => 'stylesheet',
        'media' => 'print',
        'onload' => 'this.media="all"; this.onload=null;',
    ];

    public function __construct(string $href, array $attributes = [])
    {
        $attributes['href'] = $href;
        $noScriptTag = LinkCriticalStylesheetTag::create($href);

        // <link rel="stylesheet" href="/path/to/my.css" media="print" onload="this.media='all'; this.onload=null;">
        parent::__construct('link', '', array_merge(self::DEFAULT_ATTRIBUTES, $attributes), $noScriptTag);
    }
}
