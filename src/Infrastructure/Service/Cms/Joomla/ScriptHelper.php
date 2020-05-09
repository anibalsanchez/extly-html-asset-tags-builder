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

namespace Extly\Infrastructure\Service\Cms\Joomla;

use Extly\Infrastructure\Support\HtmlAsset\Asset\InlineScriptTag;
use Extly\Infrastructure\Support\HtmlAsset\Asset\InlineStyleTag;
use Extly\Infrastructure\Support\HtmlAsset\Asset\LinkStylesheetTag;
use Extly\Infrastructure\Support\HtmlAsset\Asset\ScriptTag;
use Extly\Infrastructure\Support\HtmlAsset\Repository as HtmlAssetRepository;
use Joomla\CMS\Factory as CMSFactory;
use Joomla\CMS\HTML\HTMLHelper as CMSHTMLHelper;
use Joomla\CMS\Version as CMSVersion;

class ScriptHelper
{
    public static function addStyleSheet($url)
    {
        return self::addDeferredStyle($url);
    }

    public static function addScript($url, $options = [], $attribs = [])
    {
        if (!isset($options['version'])) {
            $options['version'] = 'auto';
        }

        return self::addScriptVersion($url, $options, $attribs);
    }

    public static function addScriptVersion($url, $options = [], $attribs = [])
    {
        if (\is_string($options) &&
            false === strpos($url, '?') &&
            'text/javascript' !== $options) {
            $url = $url.'?'.$options;
        }

        if (!\is_array($attribs)) {
            $attribs = [];
        }

        return self::addDeferredScript($url, $attribs);
    }

    public static function addScriptDeclaration($script)
    {
        CMSFactory::getDocument()->addScriptDeclaration($script);

        // Alternative XT Html Asset Tags Builder
        $inlineScriptTag = InlineScriptTag::create($script);
        HtmlAssetRepository::getInstance()->push($inlineScriptTag);
    }

    public static function addStyleDeclaration($style)
    {
        CMSFactory::getDocument()->addStyleDeclaration($style);

        // Alternative XT Html Asset Tags Builder
        $inlineStyleTag = InlineStyleTag::create($style);
        HtmlAssetRepository::getInstance()->push($inlineStyleTag);
    }

    /**
     * addDeferredExtensionScript.
     *
     * Example: ScriptHelper::addDeferredExtensionScript('lib_xtdir4alg/app/autocomplete.min.js');
     *
     * @param string $extensionScript Param
     * @param mixed  $attribs         Html Attributes
     */
    public static function addDeferredExtensionScript($extensionScript, $attribs = [])
    {
        $uri = self::resolveExtensionScriptUri($extensionScript, $attribs);
        self::addScriptToDocument($uri, $attribs);

        // Alternative XT Html Asset Tags Builder
        $scriptTag = ScriptTag::create(self::addMediaVersion($uri), $attribs);
        HtmlAssetRepository::getInstance()->push($scriptTag);
    }

    /**
     * addDeferredScript.
     *
     * Example: ScriptHelper::addDeferredScript('https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js');
     *
     * @param string $extensionScriptHref Param
     * @param mixed  $attribs             Html Attributes
     */
    public static function addDeferredScript($extensionScriptHref, $attribs = [])
    {
        $defaultAttribs = ['defer' => true];
        $attribs = array_merge($defaultAttribs, $attribs);

        CMSFactory::getDocument()->addScript($extensionScriptHref, ['version' => 'auto'], $attribs);

        // Alternative XT Html Asset Tags Builder
        $scriptTag = ScriptTag::create($extensionScriptHref, $attribs);
        HtmlAssetRepository::getInstance()->push($scriptTag);
    }

    /**
     * addDeferredStyle.
     *
     * @deprecated
     *
     * @param mixed $stylesheetUri
     */
    public static function addDeferredStyle($stylesheetUri)
    {
        return self::addDeferredStylesheet($stylesheetUri);
    }

    /**
     * addDeferredStylesheet.
     *
     * Example: ScriptHelper::addDeferredStyle('https://cdn.jsdelivr.net/npm/...instantsearch.min.css');
     *
     * @param string $stylesheetUri Param
     */
    public static function addDeferredStylesheet($stylesheetUri)
    {
        CMSFactory::getDocument()
            ->addCustomTag('<link rel="preload" href="'.
            $stylesheetUri.'" as="style" onload="this.onload=null;this.rel=\'stylesheet\'">');
        CMSFactory::getDocument()
            ->addCustomTag('<noscript><link rel="stylesheet" href="'.
            $stylesheetUri.'"></noscript>');

        // Alternative XT Html Asset Tags Builder
        $linkStylesheetTag = LinkStylesheetTag::create($stylesheetUri);
        HtmlAssetRepository::getInstance()->push($linkStylesheetTag);
    }

    public static function addDeferredExtensionStylesheet($extensionRelativeScript, $options = [])
    {
        $uri = self::resolveExtensionStylesheetUri($extensionRelativeScript, $options);

        return self::addDeferredStylesheet($uri, $options);
    }

    /**
     * addInlineStylesheet.
     *
     * Example: ScriptHelper::addInlineStylesheet('mod_xtdir4alg_autocomplete/xtdir4alg_autocomplete.min.css');
     *
     * @param string $extensionStyle
     * @param mixed  $extensionRelativeStylesheet
     * @param mixed  $options
     */
    public static function addInlineStylesheet($extensionRelativeStylesheet, $options = [])
    {
        $uri = self::resolveExtensionStylesheetUri($extensionRelativeStylesheet, $options);
        $filePath = JPATH_ROOT.$uri;

        // The Uri can be mapped to a directory (subfolders?)
        if (file_exists($filePath)) {
            $styleDeclaration = file_get_contents($filePath);

            CMSFactory::getDocument()->addStyleDeclaration($styleDeclaration);

            // Alternative XT Html Asset Tags Builder
            $inlineStyleTag = InlineStyleTag::create($styleDeclaration);
            HtmlAssetRepository::getInstance()->push($inlineStyleTag);

            return true;
        }

        // Just load the StyleSheet
        CMSFactory::getDocument()->addStyleSheet($uri);

        // Alternative XT Html Asset Tags Builder
        $stylesheetTag = LinkStylesheetTag::create(self::addMediaVersion($uri));
        HtmlAssetRepository::getInstance()->push($stylesheetTag);

        return true;
    }

    /**
     * addInlineScript (Extension).
     *
     * Example: ScriptHelper::addInlineScript('lib_xtdir4alg/app/autocomplete.min.js');
     *
     * @param mixed $extensionRelativeScript
     * @param mixed $options
     */
    public static function addInlineScript($extensionRelativeScript, $options = [])
    {
        $uri = self::resolveExtensionScriptUri($extensionRelativeScript, $options);
        $filePath = JPATH_ROOT.$uri;

        // The Uri can be mapped to a directory (subfolders?)
        if (file_exists($filePath)) {
            $scriptDeclaration = file_get_contents($filePath);

            CMSFactory::getDocument()->addScriptDeclaration($scriptDeclaration);

            // Alternative XT Html Asset Tags Builder
            $inlineScriptTag = InlineScriptTag::create($scriptDeclaration);
            HtmlAssetRepository::getInstance()->push($inlineScriptTag);

            return true;
        }

        return self::addDeferredExtensionScript($extensionRelativeScript);
    }

    private function resolveExtensionStylesheetUri($extensionRelativeStylesheet, $options = [])
    {
        $defaultOptions = ['relative' => true, 'pathOnly' => true];
        $options = array_merge($defaultOptions, $options);

        return CMSHTMLHelper::stylesheet($extensionRelativeStylesheet, $options);
    }

    private function resolveExtensionScriptUri($extensionRelativeScript, $options = [])
    {
        $defaultOptions = ['relative' => true, 'pathOnly' => true];
        $options = array_merge($defaultOptions, $options);

        return CMSHTMLHelper::script(
            $extensionRelativeScript,
            $options
        );
    }

    private static function addScriptToDocument($include, $attribs)
    {
        // Pasted from libraries/src/HTML/HTMLHelper.php, 730
        $options = [];

        // If inclusion is required
        $document = CMSFactory::getDocument();

        // If there is already a version hash in the script reference (by using deprecated MD5SUM).
        if ($pos = false !== strpos($include, '?')) {
            $options['version'] = substr($include, $pos + 1);
        }

        if (!isset($options['version'])) {
            $options['version'] = 'auto';
        }

        $document->addScript($include, $options, $attribs);
    }

    private static function addMediaVersion($uri)
    {
        $mediaversion = (new CMSVersion())->getMediaVersion();

        if (false !== strpos($uri, '?')) {
            return $uri.'?'.$mediaversion;
        }

        return $uri.'&'.$mediaversion;
    }
}
