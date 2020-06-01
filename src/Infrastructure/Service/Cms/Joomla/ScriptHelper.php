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
use Joomla\CMS\Uri\Uri as CMSUri;
use Joomla\CMS\Version as CMSVersion;

class ScriptHelper
{
    const CLIENT_FRONTEND = 1;
    const CLIENT_ADMINISTRATOR = 0;

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
     * @param mixed  $options
     */
    public static function addDeferredExtensionScript($extensionScript, $options = [], $attribs = [])
    {
        $uriWithMediaVersion = self::addClient(
            self::addMediaVersion(
                self::resolveExtensionScriptUri($extensionScript, $options)
            ),
            $options
        );
        self::addScriptToDocument($uriWithMediaVersion, $options, $attribs);

        // Alternative XT Html Asset Tags Builder
        $scriptTag = ScriptTag::create($uriWithMediaVersion, $attribs);
        HtmlAssetRepository::getInstance()->push($scriptTag);
    }

    public static function addDeferredExtensionStylesheet($extensionRelativeScript, $options = [], $attribs = [])
    {
        $uriWithMediaVersion = self::addClient(
            self::addMediaVersion(
                self::resolveExtensionStylesheetUri($extensionRelativeScript, $options)
            ),
            $options
        );

        return self::addDeferredStylesheet($uriWithMediaVersion, $options, $attribs);
    }

    /**
     * addDeferredScript.
     *
     * Example: ScriptHelper::addDeferredScript('https://cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js');
     *
     * @param string $extensionScriptUri Param
     * @param mixed  $attribs            Html Attributes
     * @param mixed  $options
     */
    public static function addDeferredScript($extensionScriptUri, $options = [], $attribs = [])
    {
        $defaultOptions = ['version' => 'auto'];
        $options = array_merge($defaultOptions, $options);

        $defaultAttribs = ['defer' => true];
        $attribs = array_merge($defaultAttribs, $attribs);

        CMSFactory::getDocument()->addScript($extensionScriptUri, $options, $attribs);

        // Alternative XT Html Asset Tags Builder
        $scriptTag = ScriptTag::create($extensionScriptUri, $attribs);
        HtmlAssetRepository::getInstance()->push($scriptTag);
    }

    /**
     * addDeferredStylesheet.
     *
     * Example:
     * ScriptHelper::addDeferredStylesheet('https://cdn.jsdelivr.net/npm/...instantsearch.min.css');
     *
     * @param string $stylesheetUri Param
     * @param mixed  $options
     * @param mixed  $attribs
     */
    public static function addDeferredStylesheet($stylesheetUri, $options = [], $attribs = [])
    {
        CMSFactory::getDocument()
            ->addCustomTag('<link rel="stylesheet" media="print" href="'.
            $stylesheetUri.'" onload="this.media=\'all\'; this.onload=null;">');
        CMSFactory::getDocument()
            ->addCustomTag('<noscript><link rel="stylesheet" href="'.
            $stylesheetUri.'"></noscript>');

        // Alternative XT Html Asset Tags Builder
        $linkStylesheetTag = LinkStylesheetTag::create($stylesheetUri, $attribs);
        HtmlAssetRepository::getInstance()->push($linkStylesheetTag);
    }

    /**
     * addInlineScript (Extension).
     *
     * Example: ScriptHelper::addInlineScript('lib_xtdir4alg/app/autocomplete.min.js');
     *
     * @param mixed $extensionRelativeScript
     * @param mixed $options
     * @param mixed $attribs
     */
    public static function addInlineExtensionScript($extensionRelativeScript, $options = [], $attribs = [])
    {
        $uri = self::resolveExtensionScriptUri($extensionRelativeScript, $options);
        $filePath = JPATH_ROOT.'/'.$uri;

        // The Uri can be mapped to a directory (subfolders?)
        if (file_exists($filePath)) {
            $scriptDeclaration = file_get_contents($filePath);

            CMSFactory::getDocument()->addScriptDeclaration($scriptDeclaration);

            // Alternative XT Html Asset Tags Builder
            $inlineScriptTag = InlineScriptTag::create($scriptDeclaration);
            HtmlAssetRepository::getInstance()->push($inlineScriptTag);

            return true;
        }

        return self::addDeferredExtensionScript($extensionRelativeScript, $options, $attribs);
    }

    /**
     * addInlineStylesheet.
     *
     * Example: ScriptHelper::addInlineStylesheet('mod_xtdir4alg_autocomplete/xtdir4alg_autocomplete.min.css');
     *
     * @param string $extensionStyle
     * @param mixed  $extensionRelativeStylesheet
     * @param mixed  $options
     * @param mixed  $attribs
     */
    public static function addInlineExtensionStylesheet($extensionRelativeStylesheet, $options = [], $attribs = [])
    {
        $uri = self::resolveExtensionStylesheetUri($extensionRelativeStylesheet, $options);
        $filePath = JPATH_ROOT.'/'.$uri;

        // The Uri can be mapped to a directory (subfolders?)
        if (file_exists($filePath)) {
            $styleDeclaration = file_get_contents($filePath);

            CMSFactory::getDocument()->addStyleDeclaration($styleDeclaration);

            // Alternative XT Html Asset Tags Builder
            $inlineStyleTag = InlineStyleTag::create($styleDeclaration);
            HtmlAssetRepository::getInstance()->push($inlineStyleTag);

            return true;
        }

        // Fallback
        return self::addDeferredExtensionStylesheet($extensionRelativeStylesheet, $options, $attribs);
    }

    public static function addMediaVersion($uri)
    {
        $mediaversion = (new CMSVersion())->getMediaVersion();

        if (false !== strpos($uri, '?')) {
            return $uri.'&'.$mediaversion;
        }

        return $uri.'?'.$mediaversion;
    }

    public static function addClient($uriRelative, $options = [])
    {
        $hasClient = \is_array($options) && isset($options['client']);

        if (!$hasClient) {
            return $uriRelative;
        }

        $client = $options['client'];

        $uriBase = CMSUri::base();

        if (self::CLIENT_FRONTEND === $client) {
            $uriBase = CMSUri::root();
        }

        return rtrim($uriBase, '/').'/'.$uriRelative;
    }

    // Alias addInlineExtensionScript
    public static function addInlineScript($extensionRelativeScript, $options = [])
    {
        return self::addInlineExtensionScript($extensionRelativeScript, $options);
    }

    // Alias addInlineExtensionStylesheet
    public static function addInlineStylesheet($extensionRelativeStylesheet, $options = [])
    {
        return self::addInlineExtensionStylesheet($extensionRelativeStylesheet, $options);
    }

    // Alias addDeferredStylesheet
    public static function addStylesheet($url)
    {
        return self::addDeferredStylesheet($url);
    }

    // Alias addScriptVersion + version auto
    public static function addScript($url, $options = [], $attribs = [])
    {
        if (\is_array($options) && !isset($options['version'])) {
            $options['version'] = 'auto';
        }

        return self::addScriptVersion($url, $options, $attribs);
    }

    // Alias addDeferredScript + options string
    public static function addScriptVersion($url, $options = [], $attribs = [])
    {
        if (\is_string($options) &&
            false === strpos($url, '?') &&
            'text/javascript' !== $options) {
            $url = $url.'?'.$options;
            $options = [];
        }

        if (!\is_array($attribs)) {
            $attribs = [];
        }

        return self::addDeferredScript($url, $options, $attribs);
    }

    /**
     * addDeferredStyle.
     *
     * @deprecated
     *
     * @param mixed $stylesheetUri
     * @param mixed $options
     * @param mixed $attribs
     */
    public static function addDeferredStyle($stylesheetUri, $options = [], $attribs = [])
    {
        return self::addDeferredStylesheet($stylesheetUri, $options = [], $attribs = []);
    }

    private function resolveExtensionStylesheetUri($extensionRelativeStylesheet, $options = [])
    {
        $defaultOptions = ['relative' => true, 'pathOnly' => true];
        $options = array_merge($defaultOptions, $options);

        $uri = CMSHTMLHelper::stylesheet($extensionRelativeStylesheet, $options);

        return ltrim($uri, '/');
    }

    private function resolveExtensionScriptUri($extensionRelativeScript, $options = [])
    {
        $defaultOptions = ['relative' => true, 'pathOnly' => true];
        $options = array_merge($defaultOptions, $options);

        $uri = CMSHTMLHelper::script(
            $extensionRelativeScript,
            $options
        );

        return ltrim($uri, '/');
    }

    private static function addScriptToDocument($scriptUri, $options = [], $attribs = [])
    {
        // Pasted from libraries/src/HTML/HTMLHelper.php, 730
        // $options = [];

        // If inclusion is required
        $document = CMSFactory::getDocument();

        // If there is already a version hash in the script reference (by using deprecated MD5SUM).
        if ($pos = false !== strpos($scriptUri, '?')) {
            $options['version'] = substr($scriptUri, $pos + 1);
        }

        if (!isset($options['version'])) {
            $options['version'] = 'auto';
        }

        $document->addScript($scriptUri, $options, $attribs);
    }
}
