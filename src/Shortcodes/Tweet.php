<?php

namespace Jvelo\Paidia\Shortcodes;

class Tweet implements Shortcode {

    public function name()
    {
        return "tweet";
    }

    public function handler()
    {
        return function($shortcode) {
            $id = $shortcode->getParameter('name');
            $theme = $shortcode->getParameter('theme') || 'dark';
            $script = <<< 'SCRIPT'
            <script defer src="//platform.twitter.com/widgets.js" charset="utf-8"></script>
            <script type='text/javascript'>
            document.addEventListener('DOMContentLoaded', function() {
                twttr.widgets.createTweet(%s, document.getElementById('tweet-%s'), { theme: '%s');
            });
            </script>
SCRIPT;
            return sprintf('<div id="tweet-%s"></div>' . $script, $id, $id, $id, $theme);
        };
    }

    public function listeners()
    {
        return [];
    }
}