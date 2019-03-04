<?php

namespace Statamic\Addons\AardvarkSeo\SuggestModes;

use Statamic\Addons\Suggest\Modes\AbstractMode;

class SocialIconSuggestMode extends AbstractMode
{
    /**
     * Return a list of social icons.
     *
     * @return array
     */
    public function suggestions()
    {
        return [
            ['value' => 'amazon', 'text' => 'Amazon'],
            ['value' => 'android', 'text' => 'Android'],
            ['value' => 'apple', 'text' => 'Apple'],
            ['value' => 'behance', 'text' => 'Behance'],
            ['value' => 'bitbucket', 'text' => 'Bitbucket'],
            ['value' => 'codepen', 'text' => 'Codepen'],
            ['value' => 'dribbble', 'text' => 'Dribbble'],
            ['value' => 'facebook', 'text' => 'Facebook'],
            ['value' => 'flickr', 'text' => 'Flickr'],
            ['value' => 'foursquare', 'text' => 'Foursquare'],
            ['value' => 'github', 'text' => 'Github'],
            ['value' => 'google-plus', 'text' => 'Google Plus'],
            ['value' => 'instagram', 'text' => 'Instagram'],
            ['value' => 'linkedin', 'text' => 'LinkedIn'],
            ['value' => 'medium', 'text' => 'Medium'],
            ['value' => 'meetup', 'text' => 'Meetup'],
            ['value' => 'pinterest', 'text' => 'Pinterest'],
            ['value' => 'reddit', 'text' => 'Reddit'],
            ['value' => 'skype', 'text' => 'Skype'],
            ['value' => 'slack', 'text' => 'Slack'],
            ['value' => 'soundcloud', 'text' => 'Soundcloud'],
            ['value' => 'spotify', 'text' => 'Spotify'],
            ['value' => 'twitch', 'text' => 'Twitch'],
            ['value' => 'twitter', 'text' => 'Twitter'],
            ['value' => 'tumblr', 'text' => 'Tumblr'],
            ['value' => 'whatsapp', 'text' => 'WhatsApp'],
            ['value' => 'yelp', 'text' => 'Yelp'],
            ['value' => 'youtube', 'text' => 'YouTube'],
        ];
    }
}
