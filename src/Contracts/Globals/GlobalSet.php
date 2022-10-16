<?php

namespace WithCandour\AardvarkSeo\Contracts\Globals;

use Illuminate\Support\Collection;
use Statamic\Fields\Blueprint;
use Statamic\Sites\Site;

interface GlobalSet
{
    /**
     * Get the ID of the global set.
     *
     * @return string
     */
    public function id(): string;

    /**
     * Get the handle of the global set.
     *
     * @return string
     */
    public function handle(): string;

    /**
     * Get the type of the global set.
     *
     * @return string
     */
    public function type(): string;

    /**
     * Get the blueprint for the global set.
     *
     * @return \Statamic\Fields\Blueprint
     */
    public function blueprint(): ?Blueprint;

    /**
     * Create a localization of the global set.
     *
     * @param \Statamic\Sites\Site
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables
     */
    public function makeLocalization(Site $site): GlobalVariables;

    /**
     * Add a localization of the global set.
     *
     * @param \WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables $localization
     * @return self
     */
    public function addLocalization(GlobalVariables $localization): self;

    /**
     * Remove a localization of the global set.
     *
     * @param \WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables $localization
     * @return self
     */
    public function removeLocalization(GlobalVariables $localization): self;

    /**
     * Get an instance of the global set in a specific locale.
     *
     * @param string $locale
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables|null
     */
    public function in(string $locale): ?GlobalVariables;

    /**
     * Get an instance of the localization in the current site.
     *
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables|null
     */
    public function inCurrentSite(): ?GlobalVariables;

    /**
     * Get an instance of the localization in the cp selected site.
     *
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables|null
     */
    public function inSelectedSite(): ?GlobalVariables;

    /**
     * Get an instance of the localization in the default site.
     *
     * @return \WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables|null
     */
    public function inDefaultSite(): ?GlobalVariables;

    /**
     * Determine whether the global set exists in a locale.
     *
     * @param string $locale
     * @return bool
     */
    public function existsIn(string $locale): bool;

    /**
     * Return all localizations of the global set.
     *
     * @return \Illuminate\Support\Collection
     */
    public function localizations(): Collection;

    /**
     * Save the global set.
     *
     * @return self
     */
    public function save(): self;
}
