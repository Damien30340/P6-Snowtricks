<?php


namespace App\Service;


class VideoEmbedLinkExtractor
{

    public function rename(string $url)
    {

        if(preg_match('@^(https\://www\.youtube\.com/watch\?v\=)([0-9A-Za-z\-\*\_\.]+)@', $url, $matches))
        {

            return 'https://www.youtube.com/embed/' .$matches[2];
        }
        if(preg_match('@^(https\:\/\/youtu\.be\/)([0-9A-Za-z\-\*\_\.]+)@', $url, $matches))
        {
            return  'https://www.youtube.com/embed/' .$matches[2];
        }

        if(preg_match('@^(\<iframe width\=\"[0-9]+\" height\=\"[0-9]+\" src\=\"https\:\/\/www\.youtube\.com\/embed\/)([0-9A-Za-z\-\*\_\.]+)@', $url, $matches))
        {
            return  'https://www.youtube.com/embed/' .$matches[2];
        }


        return $url;
    }
}