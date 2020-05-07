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

final class LinkStylesheetTag extends HtmlAssetTagAbstract implements HtmlAssetTagInterface
{
    use CreatorTrait;

    public function __construct(string $href, array $attributes = [])
    {
        $attributes['href'] = $href;

        parent::__construct(
            'link',
            '',
            array_merge(
                LinkPreloadStylesheetTag::DEFAULT_ATTRIBUTES,
                $attributes
            )
        );
    }
}
