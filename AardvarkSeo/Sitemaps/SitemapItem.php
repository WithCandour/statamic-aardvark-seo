<?php

namespace Statamic\Addons\AardvarkSeo\Sitemaps;

class SitemapItem
{
    const DEFAULT_CHANGEFREQ = 'daily';
    const DEFAULT_PRIORITY = '0.5';

    /**
     * Create a new sitemap item
     *
     * @param \Statamic\Data\Content\Content $content
     */
    public function __construct(\Statamic\Data\Content\Content $content)
    {
        $this->data_object = $content;
        $this->data = collect($this->data_object->data());
    }

    /**
     * Get the url of the current item
     *
     * @return string
     */
    public function getUrl()
    {
        $item = $this->data_object;
        return $item->get('canonical_url') ?: $item->absoluteUrl();
    }

    /**
     * Get a formatted version of the last modified attribute
     *
     * @return string
     */
    public function getFormattedLastMod()
    {
        return $this->data_object->lastModified()->format('Y-m-d\TH:i:sP');
    }

    /**
     * Return the changefrequency with a fallback
     *
     * @return string
     */
    public function getChangeFreq()
    {
        return $this->data->get('sitemap_changefreq') ?: self::DEFAULT_CHANGEFREQ;
    }

    /**
     * Return the priority with a fallback
     *
     * @return string
     */
    public function getPriority()
    {
        return $this->data->get('sitemap_priority') ?: self::DEFAULT_PRIORITY;
    }
}
