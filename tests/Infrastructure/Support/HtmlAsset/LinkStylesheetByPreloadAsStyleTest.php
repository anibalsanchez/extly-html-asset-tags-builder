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

use Extly\Infrastructure\Support\HtmlAsset\Asset\LinkStylesheetByPreloadAsStyle;
use Extly\Infrastructure\Support\HtmlAsset\HtmlAssetTagsBuilder;
use PHPUnit\Framework\TestCase;

/**
 * @coversNothing
 */
class LinkStylesheetByPreloadAsStyleTest extends TestCase
{
    public function testBuildTag()
    {
        $linkDeferStylesheetTag = LinkStylesheetByPreloadAsStyle::create(
            'https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.0.6/tailwind.min.css'
        );
        $htmlAssetBuilder = HtmlAssetTagsBuilder::create();
        $tag = $htmlAssetBuilder->buildTag($linkDeferStylesheetTag);

        $this->assertSame(
            '<link rel="preload" as="style" onload="this.onload=null;this.rel = &quot;stylesheet&quot;" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/1.0.6/tailwind.min.css">',
            $tag
        );
    }
}
