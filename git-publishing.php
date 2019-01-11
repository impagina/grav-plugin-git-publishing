<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

use Grav\Common\File\CompiledYamlFile;
use Grav\Common\File\CompiledMarkdownFile;

use Symfony\Component\Yaml\Yaml;

require_once __DIR__ . '/Markdown.php';
use GitPublishing\Markdown;

require_once __DIR__ . '/Utilities.php';
use GitPublishing\Utilities;

/**
 * Class GitPublishingPlugin
 * @package Grav\Plugin
 */
class GitPublishingPlugin extends Plugin
{
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0]
        ];
    }

    public function onPluginsInitialized()
    {
        // Don't proceed if we are in the admin plugin
        if ($this->isAdmin()) {
            return;
        }
        // echo("<pre>".print_r($this->grav['uri']->route(), 1)."</pre>");
        // echo("<pre>".print_r($this->grav['uri']->path(), 1)."</pre>");

        // Enable the main event we are interested in
        $this->enable([
            'onPageContentRaw' => ['onPageContentRaw', 0],
            'onPageNotFound' => ['onPageNotFound', 1000]
        ]);
    }

    /*
    public function onPageInitialized(Event $event)
    {
        // echo("<pre>".print_r($event, 1)."</pre>");
    }
    */

    public function onPageNotFound(Event $event)
    {
        $catchingRoutes = $this->config->get('plugins.git-publishing.routes');
        $route = $this->grav['uri']->path();
        $url = Null;
        foreach ($catchingRoutes as $catchingRoute) {
            if ($route != $catchingRoute && $catchingRoute == substr($route, 0, strlen($catchingRoute))) {
                $url = $catchingRoute;
                break;
            }
        }
        if ($url) {
            $event->page = $this->grav['pages']->dispatch($url);
            $event->stopPropagation();
        }
    }

    /**
     * @param Event $event
     */
    public function onPageContentRaw(Event $event)
    {
        $page = $event['page'];
        $config = $this->mergeConfig($page);

        if (!$config->get('active')) {
            return;
        }

        if (!$config->get('project')) {
            throw   new \RuntimeException('Git Publishing: no project file defined');
        }

        // echo("<pre>".print_r($this->grav['uri']->route(), 1)."</pre>");
        $route = $page->route();
        $path = $page->path();

        $projectFilename = Utilities::joinPath($path, $config->get('project'));
        $projectPath = dirname($projectFilename);

        if (!file_exists($projectFilename)) {
            throw   new \RuntimeException('Git Publishing: '.$config->get('project').' not found.');
        }

        $projectConfig = CompiledYamlFile::instance($projectFilename)->content();
        // echo("<pre>".print_r($projectConfig, 1)."</pre>");

        $language = $this->getLanguage($projectConfig, $config);

        $bookPage = trim(substr($this->grav['uri']->route(), strlen($route)), '/');
        if (!in_array($bookPage, $projectConfig['chapters'])) {
            // TODO: or rather trigger a 404?
            $bookPage = $projectConfig['toc'];
        }


        // TODO: empty dir if no content_dir is defined? or error?
        $contentPath = Utilities::joinPath($projectPath, $projectConfig['content_dir']);

        $contentHeader = '';

        if ($bookPage !== '') {
            $chapterFile = sprintf('%s-%s.md', $bookPage, $language);
            // TODO: check what's the right link is... it works ok this way, but it feels wrong
            // TODO: use the translations file
            $contentHeader .= "[Table of Contents](.)\n";
        } else {
            $chapterFile = sprintf('%s-%s.md', $projectConfig['toc'], $language);
        }

        $chapterPath = Utilities::joinPath($contentPath, $chapterFile);
        $markdownFile = CompiledMarkdownFile::instance($chapterPath)->content();

        $pageContent = $page->getRawContent();
        $content = $markdownFile['markdown'];
        // $event['page']->setRawContent($pageContent . "\n\n" . $content);
        $markdown = new Markdown();
        // TODO: find a way to get the relative media path out of Grav
        $relativeMediaPath = substr($page->media()->path(), strlen(dirname($_SERVER['SCRIPT_FILENAME'])));
        // echo("<pre>: ".print_r($this->grav, 1)."</pre>");
        $markdown->linkBasePath = basename($page->route());
        $markdown->imageBasePath = $relativeMediaPath.'/'.trim(substr($contentPath, strlen($path)), '/').'/';
        $markdown->hasLanguageSuffix = true;

        if ($contentHeader !== '') {
            $contentHeader .= "\n\n";
        }

        $event['page']->setRawContent($pageContent . "\n\n" . $contentHeader. $markdown->text($content));
    }

    private function getLanguage($projectConfig, $pageConfig)
    {
        // TODO: initialize with the current grav language
        $language = '';
        $language = $pageConfig->get('language');
        if ($language !== '') {
            // TODO: trigger an error if the language is not in the list or if there is no list
            if (!in_array($language, $projectConfig['languages'])) {
                $language = '';
            }
        }
        if ($language === '') {
            $language = $projectConfig['languages'][0];
        }
        return $language;
    }
}
