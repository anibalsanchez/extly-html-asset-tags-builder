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

namespace Extly\Infrastructure\Support\HtmlAsset;

use Extly\Infrastructure\Support\HtmlAsset\Asset\HtmlAssetTagInterface;
use Illuminate\Support\Collection;

final class Repository
{
    public const HTML_POSITION = 'position';
    public const HTML_PRIORITY = 'priority';

    public const GLOBAL_POSITION_HEAD = 'head';
    public const GLOBAL_POSITION_BODY = 'bottom';

    private $assetTagCollection;

    public function __construct()
    {
        $this->assetCollection = Collection::make();
    }

    public function push(HtmlAssetTagInterface $htmlAsset)
    {
        $this->assetCollection->push($htmlAsset);

        return $this;
    }

    public function getAssetTagsByPosition($positionName)
    {
        return $this->assetCollection
            ->filter(function (HtmlAssetTagInterface $item) use ($positionName) {
                return $item->getPosition() === $positionName;
            })
            ->sortBy(function (HtmlAssetTagInterface $item) {
                return $item->getPriority();
            });
    }

    public function getNoScriptContentTags()
    {
        return $this->assetCollection->map(function (HtmlAssetTagInterface $item) {
            return $item->getNoScriptContentTag();
        })->filter();
    }
}
