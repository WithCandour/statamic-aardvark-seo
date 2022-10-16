<?php

namespace WithCandour\AardvarkSeo\Globals;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\App;
use Statamic\Facades\Site;
use Statamic\Fields\Blueprint;
use Statamic\Sites\Site as StatamicSite;
use Statamic\Support\Traits\FluentlyGetsAndSets;
use WithCandour\AardvarkSeo\Blueprints\CP\GeneralSettingsBlueprint;
use WithCandour\AardvarkSeo\Blueprints\CP\MarketingSettingsBlueprint;
use WithCandour\AardvarkSeo\Blueprints\CP\SitemapSettingsBlueprint;
use WithCandour\AardvarkSeo\Blueprints\CP\SocialSettingsBlueprint;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalSet as Contract;
use WithCandour\AardvarkSeo\Contracts\Globals\GlobalVariables;
use WithCandour\AardvarkSeo\Contracts\Stache\Repositories\GlobalsRepository;

class GlobalSet implements Contract
{
    use FluentlyGetsAndSets;

    /**
     * @var string
     */
    protected $handle;

    /**
     * @var array<GlobalVariables>
     */
    protected array $localizations = [];

    /**
     * @inheritDoc
     */
    public function id(): string
    {
        return $this->handle();
    }

    public function handle(string $handle = null): string
    {
        return $this->fluentlyGetOrSet('handle')->args(func_get_args());
    }

    /**
     * @inheritDoc
     */
    public function type(): string
    {
        // TODO: Replace this with enums once we can enforce 8.1 as the minumum PHP version
        return $this->handle();
    }

    /**
     * @inheritDoc
     */
    public function blueprint(): ?Blueprint
    {
        /**
         * @var \WithCandour\AardvarkSeo\Contracts\Blueprints\Blueprint|null
         */
        $class = null;

        // TODO: Use dependency injection for this
        switch($this->type()) {
            case 'general':
                $class = new GeneralSettingsBlueprint();
                break;
            case 'marketing':
                $class = new MarketingSettingsBlueprint();
                break;
            case 'social':
                $class = new SocialSettingsBlueprint();
                break;
            case 'sitemap':
                $class = new SitemapSettingsBlueprint();
                break;
        }

        if (!$class) {
            return null;
        }

        return $class::requestBlueprint();
    }

    /**
     * @inheritDoc
     */
    public function makeLocalization(StatamicSite $site): GlobalVariables
    {
        return (new GlobalVariables)
            ->globalSet($this)
            ->locale($site->locale());
    }

    /**
     * @inheritDoc
     */
    public function addLocalization(GlobalVariables $localization): self
    {
        $this->localizations[$localization->locale()] = $localization;
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function removeLocalization(GlobalVariables $localization): self
    {
        unset($this->localizations[$localization->locale()]);
        return $this;
    }

    /**
     * @inheritDoc
     */
    public function in(string $locale): ?GlobalVariables
    {
        return $this->localizations[$locale] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function inCurrentSite(): ?GlobalVariables
    {
        return $this->in(Site::current()->handle());
    }

    /**
     * @inheritDoc
     */
    public function inSelectedSite(): ?GlobalVariables
    {
        return $this->in(Site::selected()->handle());
    }

    /**
     * @inheritDoc
     */
    public function inDefaultSite(): ?GlobalVariables
    {
        return $this->in(Site::default()->handle());
    }

    /**
     * @inheritDoc
     */
    public function existsIn(string $locale): bool
    {
        return $this->in($locale) !== null;
    }

    /**
     * @inheritDoc
     */
    public function localizations(): Collection
    {
        return collect($this->localizations ?? []);
    }

    /**
     * @inheritDoc
     */
    public function save(): self
    {
        $repository = App::make(GlobalsRepository::class);

        $isNew = is_null($repository->find($this->id()));

        $repository->save($this);

        return $this;
    }
}
