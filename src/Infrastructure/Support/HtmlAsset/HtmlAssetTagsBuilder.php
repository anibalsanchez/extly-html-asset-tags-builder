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

namespace Extly\Infrastructure\Support\HtmlAsset;

use Extly\Infrastructure\Creator\CreatorTrait;
use Extly\Infrastructure\Support\HtmlAsset\Asset\HtmlAssetTagInterface;
use Illuminate\Support\Collection;
use Studiow\HTML\Element;

final class HtmlAssetTagsBuilder
{
    use CreatorTrait;

    private $repository;

    public function __construct(Repository $repository = null)
    {
        if (!$repository) {
            // No Container, so let's singleton it
            $repository = Repository::getInstance();
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

    public function buildTag(HtmlAssetTagInterface $assetTag)
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

    public function buildNoScriptTag(HtmlAssetTagInterface $noScriptContentTag)
    {
        $renderedNoScriptContentTag = $this->buildTag($noScriptContentTag);

        return (string) (new Element(
            'noscript',
            $renderedNoScriptContentTag
        ));
    }

    private function build($assetTags)
    {
        $buffer = [];

        foreach ($assetTags as $assetTag) {
            $buffer[] = $this->buildTag($assetTag);
        }

        return implode('', $buffer);
    }

    private function buildNoScriptTags($assetTags)
    {
        $buffer = [];

        foreach ($assetTags as $assetTag) {
            $buffer[] = $this->buildNoScriptTag($assetTag);
        }

        return implode('', $buffer);
    }
}
