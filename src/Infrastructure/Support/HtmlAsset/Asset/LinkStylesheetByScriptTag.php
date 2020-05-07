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
use Extly\Infrastructure\Support\HtmlAsset\Repository;

final class LinkStylesheetByScriptTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    use CreatorTrait;

    public function __construct(string $href, array $attributes = [])
    {
        $defaultAttributes = [
            'position' => Repository::GLOBAL_POSITION_BODY,
        ];

        $script = '!function(e){var t=document.createElement("link");t.rel="stylesheet",t.href="'.
            $href.
            '",t.type="text/css";var n=document.getElementsByTagName("link")[0];n.parentNode.insertBefore(t,n)}();';
        $noScriptTag = LinkCriticalStylesheetTag::create($href);

        parent::__construct('script', $script, array_merge($defaultAttributes, $attributes), $noScriptTag);
    }
}
