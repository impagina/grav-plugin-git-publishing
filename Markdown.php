<?php

namespace GitPublishing;

class Markdown extends \ParsedownExtra
{

    public $linkBasePath = '';
    public $imageBasePath = '';
    public $hasLanguageSuffix = false;

    protected function inlineImage($excerpt)
    {
        $image = parent::inlineImage($excerpt);

        if (!isset($image))
        {
            return null;
        }

        $image['element']['attributes']['src'] = $this->imageBasePath . $image['element']['attributes']['src'];

        return $image;
    }

    protected function inlineLink($excerpt)
    {
        $link = parent::inlineLink($excerpt);
        if (!isset($link))
        {
            return null;
        }

        // echo("<pre>".print_r($link, 1)."</pre>");
        $elementHref = $link['element']['attributes']['href'];
        // $fileInfo = pathinfo($elementHref, PATHINFO_EXTENSION);
        $fileInfo = pathinfo($elementHref);
        // echo("<pre>".print_r($fileInfo, 1)."</pre>");
        if ($fileInfo['extension'] == 'md') {
            // TODO: remove the language ext if any
            $link['element']['attributes']['href'] = $this->linkBasePath.'/'.$fileInfo['filename'];
            if ($this->hasLanguageSuffix) {
                // TODO: not sure that -3 is the best solution
                $link['element']['attributes']['href'] = substr($link['element']['attributes']['href'], 0, -3);
            }
        }
        return $link;
    }

}
