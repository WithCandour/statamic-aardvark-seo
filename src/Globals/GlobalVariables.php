<?php

namespace WithCandour\AardvarkSeo\Globals;

use ArrayAccess;
use Illuminate\Contracts\Support\Arrayable;
use Statamic\Contracts\Data\Augmented;
use Statamic\Contracts\Data\Localization;
use Statamic\Data\ContainsData;
use Statamic\Data\ExistsAsFile;
use Statamic\Data\HasAugmentedInstance;
use Statamic\Data\HasOrigin;
use Statamic\Facades\Site;
use Statamic\Facades\Stache;
use Statamic\Fields\Blueprint;
use Statamic\GraphQL\ResolvesValues;
use Statamic\Sites\Site as StatamicSite;
use Statamic\Support\Traits\FluentlyGetsAndSets;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables as Contract;

class GlobalVariables implements Contract, Localization, ArrayAccess, Arrayable
{
    use ExistsAsFile, ContainsData, HasAugmentedInstance, HasOrigin, FluentlyGetsAndSets, ResolvesValues;

    /**
     * @var \WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet
     */
    protected GlobalSet $set;

    /**
     * @var string $locale
     */
    protected string $locale;

    public function __construct()
    {
        $this->data = \collect();
    }

    /**
     * @inheritDoc
     */
    public function globalSet(?GlobalSet $set = null): GlobalSet
    {
        return $this->fluentlyGetOrSet('set')->args(func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function locale(?string $locale = null): string
    {
        return $this->fluentlyGetOrSet('locale')->args(func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function id(): string
    {
        return $this->globalSet()->id();
    }

    /**
     * @inheritDoc
     */
    public function handle(): string
    {
        return $this->globalSet()->handle();
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        // TODO: Replace this with enums once we can enforce 8.1 as the minumum PHP version
        return $this->globalSet()->type();
    }

    /**
     * @inheritDoc
     */
    public function blueprint(): ?Blueprint
    {
        return $this->globalSet()->blueprint();
    }

    /**
     * @inheritDoc
     */
    public function path(): string
    {
        return vsprintf('%s/%s%s.%s', [
            rtrim(Stache::store('globals')->directory(), '/'),
            Site::hasMultiple() ? $this->locale().'/' : '',
            $this->handle(),
            'yaml',
        ]);
    }

    /**
     * @inheritDoc
     */
    public function save(): void
    {
        $this->globalSet()
            ->addLocalization($this)
            ->save();
    }

    /**
     * @inheritDoc
     */
    public function site(): StatamicSite
    {
        return Site::get($this->locale());
    }

    /**
     * @inheritDoc
     */
    public function fileData(): array
    {
        $data = $this->data()->all();

        if ($this->hasOrigin()) {
            $data['origin'] = $this->origin()->locale();
        }

        return $data;
    }

    /**
     * @inheritDoc
     */
    public function reference(): string
    {
        return "aardvark-seo-globals::{$this->id()}";
    }

    /**
     * @inheritDoc
     */
    public function getOriginByString($origin)
    {
        return $this->globalSet()->in($origin);
    }

    /**
     * @inheritDoc
     */
    public function newAugmentedInstance(): Augmented
    {
        return new AugmentedGlobalVariables($this);
    }
}
