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

namespace Tests\Infrastructure\Support\HtmlAsset;

use Extly\Infrastructure\Support\HtmlAsset\Asset\LinkDeferStylesheetTag;
use Extly\Infrastructure\Support\HtmlAsset\HtmlAssetTagsBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class LinkDeferStylesheetTagTest extends TestCase
{
    public function testBuildTag()
    {
        $linkDeferStylesheetTag = LinkDeferStylesheetTag::create(
            'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.0.6/tailwind.min.css'
        );
        $htmlAssetBuilder = HtmlAssetTagsBuilder::create();
        $tag = $htmlAssetBuilder->buildTag($linkDeferStylesheetTag);

        $this->assertSame(
            '<script>!function(e){var t=document.createElement("link");t.rel="stylesheet",t.href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.0.6/tailwind.min.css",t.type="text/css";var n=document.getElementsByTagName("link")[0];n.parentNode.insertBefore(t,n)}();</script>',
            $tag
        );
    }
}
