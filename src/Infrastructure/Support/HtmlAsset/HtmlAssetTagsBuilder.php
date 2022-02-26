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
use Extly\Infrastructure\Support\HtmlAsset\HTML\Element;
use Illuminate\Support\Collection;

final class HtmlAssetTagsBuilder
{
    private $repository;

    public function __construct(Repository $repository = null)
    {
        if (!$repository) {
            $this->repository = new Repository();

            return;
        }

        $this->repository = $repository;
    }

    public function generate($positionName)
    {
        $noScriptContentTags = new Collection();
        $assetTags = $this->repository->getAssetTagsByPosition($positionName);

        if (Repository::GLOBAL_POSITION_HEAD === $positionName) {
            $noScriptContentTags = $this->repository->getNoScriptContentTags();
        }

        $renderedAssetTags = $this->build($assetTags);

        if ($noScriptContentTags->isEmpty()) {
            return $renderedAssetTags;
        }

        $renderedNoScriptContentTags = $this->buildNoScriptTags($noScriptContentTags);

        return $renderedAssetTags.$renderedNoScriptContentTags;
    }

    public function buildTag(HtmlAssetTagInterface $assetTag): string
    {
        return (string) (new Element(
            $assetTag->getTag(),
            $assetTag->getInnerHTML(),
            $assetTag->getAttributes()
                ->forget(Repository::HTML_POSITION)
                ->forget(Repository::HTML_PRIORITY)
                ->toArray()
        ));
    }

    public function buildNoScriptTag(HtmlAssetTagInterface $noScriptContentTag): string
    {
        $renderedNoScriptContentTag = $this->buildTag($noScriptContentTag);

        return (string) (new Element(
            'noscript',
            $renderedNoScriptContentTag
        ));
    }

    private function build(Collection $assetTags): string
    {
        $buffer = $assetTags->map(function (HtmlAssetTagInterface $assetTag) {
            return $this->buildTag($assetTag);
        });

        return implode('', $buffer->toArray());
    }

    private function buildNoScriptTags(Collection $assetTags)
    {
        $buffer = $assetTags->map(function (HtmlAssetTagInterface $assetTag) {
            return $this->buildNoScriptTag($assetTag);
        });

        return implode('', $buffer->toArray());
    }
}
