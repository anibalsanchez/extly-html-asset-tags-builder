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

use Extly\Infrastructure\Support\HtmlAsset\Repository;
use Illuminate\Support\Collection;

class HtmlAssetTagAbstract
{
    protected $tag;

    protected $innerHtml;

    protected $attributes;

    protected $noScriptContentTag;

    protected function __construct(
        string $tag,
        string $innerHtml,
        array $attributes = [],
        HtmlAssetTagInterface $htmlAssetTag = null
    ) {
        $this->tag = $tag;
        $this->innerHtml = $innerHtml;
        $this->attributes = Collection::make($attributes);
        $this->noScriptContentTag = $htmlAssetTag;
    }

    /**
     * Get the value of tag.
     */
    public function getTag(): string
    {
        return $this->tag;
    }

    /**
     * Get the value of innerHtml.
     */
    public function getInnerHtml(): string
    {
        return $this->innerHtml;
    }

    /**
     * Get the value of attributes.
     */
    public function getAttributes(): Collection
    {
        return clone $this->attributes;
    }

    public function getPosition(): string
    {
        if (!$this->attributes->has(Repository::HTML_POSITION)) {
            return Repository::GLOBAL_POSITION_HEAD;
        }

        return $this->attributes->get(Repository::HTML_POSITION);
    }

    public function getPriority(): int
    {
        if (!$this->attributes->has(Repository::HTML_PRIORITY)) {
            return 100;
        }

        return $this->attributes->get(Repository::HTML_PRIORITY);
    }

    public function getNoScriptContentTag()
    {
        return $this->noScriptContentTag;
    }
}
