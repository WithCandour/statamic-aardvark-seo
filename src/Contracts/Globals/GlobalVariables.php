<?php

namespace WithCandour\AardvarkSeo\Contracts\Globals;

use Statamic\Fields\Blueprint;
use Statamic\Sites\Site;

interface GlobalVariables
{
    /**
     * Get or set the global set for these variables.
     *
     * @param \WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet|null
     * @return self
     */
    public function globalSet(?GlobalSet $set = null);

    /**
     * Get the ID of the global set that the variables belong to.
     *
     * @return string
     */
    public function id(): string;

    /**
     * Get the handle of the global set that the variables belong to.
     *
     * @return string
     */
    public function handle(): string;

    /**
     * Get the type of global set that the variables belong to.
     *
     * @return string
     */
    public function type(): string;

    /**
     * Get the blueprint that these variables belong to.
     *
     * @return \Statamic\Fields\Blueprint|null
     */
    public function blueprint(): ?Blueprint;

    /**
     * Get the path of the file where these globals are stored.
     *
     * @return string
     */
    public function path(): string;

    /**
     * Save the variables.
     *
     * @return void
     */
    public function save(): void;

    /**
     * Get the site that these variables belong to.
     *
     * @return \Statamic\Sites\Site
     */
    public function site(): Site;

    /**
     * Get the file data for these variables.
     *
     * @return array
     */
    public function fileData(): array;

    /**
     * Get a reference for these variables.
     *
     * @return string
     */
    public function reference(): string;
}
