<?php

namespace WithCandour\AardvarkSeo\Parsers;

use Statamic\Facades\Collection;
use WithCandour\AardvarkSeo\Blueprints\CP\DefaultsSettingsBlueprint;
use WithCandour\AardvarkSeo\Blueprints\CP\GeneralSettingsBlueprint;
use WithCandour\AardvarkSeo\Blueprints\CP\MarketingSettingsBlueprint;
use WithCandour\AardvarkSeo\Blueprints\CP\SocialSettingsBlueprint;
use WithCandour\AardvarkSeo\Blueprints\CP\OnPageSeoBlueprint;
use WithCandour\AardvarkSeo\Facades\AardvarkStorage;

/**
 * Helper class for parsing on-page data
 */
class PageDataParser
{
    /**
     * Return the aardvark data we'll be using on the page
     *
     * @param Illumniate\Support\Collection $ctx
     *
     * @return array
     */
    public static function getData($ctx)
    {
        $defaults = self::getDefaults($ctx);

        $bp = OnPageSeoBlueprint::requestBlueprint();
        $fields_to_map = $bp->fields()->items()->mapWithKeys(function($field) {
            return [$field['handle'] =>
                [
                    'handle' => $field['handle'],
                    'type' => $field['field']['type']
                ]
            ];
        });

        $data = $ctx->map(function($value, $field) use ($defaults, $fields_to_map) {
            if($bp_field = $fields_to_map->get($field)) {
                switch($bp_field['type']) {
                    case 'toggle':
                        return $defaults->get($field) ?: $value->raw();
                    default:
                        return $value->raw() ? $value : $defaults->get($field);
                }
            }
            return $value;
        });

        $data = self::populateAdditionalData($data, $ctx);

        return $data;
    }

    /**
     * Return the current site's defaults, if we're working with
     * a multisite setup then merge the default site defaults
     * into the localized ones. No variables left behind!
     *
     * @param Illumniate\Support\Collection $ctx
     *
     * @return Illuminate\Support\Collection
     */
    public static function getDefaults($ctx)
    {
        $type = $ctx->get('is_term') ? 'taxonomies' : 'collections';

        switch($type) {
            case 'taxonomies':
                $repo = $ctx->get('taxonomy');
                break;
            case 'collections':
                $repo = $ctx->get('collection');
                break;
            default:
                $repo = null;
        }

        /**
         * Exception routes and taxonomy indexes - and anything else which
         * doesn't 'belong' to a collection
         */
        if(!$repo) {
            $repo = Collection::findByHandle('pages');
        }

        $storage_path = "defaults/{$type}_{$repo->handle()}";
        $defaults = self::getSettingsBlueprintWithValues($ctx, $storage_path, new DefaultsSettingsBlueprint());

        return $defaults;
    }

    /**
     * Populate the page data with some generated fields
     *
     * @param Illuminate\Support\Collection $data
     * @param Illuminate\Support\Collection $ctx
     *
     * @return Illuminate\Support\Collection
     */
    private static function populateAdditionalData($data, $ctx)
    {
        $populated = [
            'calculated_title' => self::generatePageTitle($data, $ctx),
            'calculated_og_image' => self::getCalculatedOgImage($data, $ctx),
            'calculated_twitter_card_type' => 'test', // TODO
            'calculated_twitter_image' => 'test', // TODO
            'aardvark_general_settings' => self::getSettingsBlueprintWithValues($ctx, 'general', new GeneralSettingsBlueprint()),
            'aardvark_marketing_settings' => self::getSettingsBlueprintWithValues($ctx, 'marketing', new MarketingSettingsBlueprint()),
            'aardvark_social_settings' => self::getSettingsBlueprintWithValues($ctx, 'social', new SocialSettingsBlueprint()),
        ];

        return $data->merge($populated);
    }

    /**
     *  Return a correctly formatted set of data from addon storage
     * -- This uses the original blueprint so that we can augment the values
     * -- getting passed to the tempalte (convert them to Statamic\Fields\Value)
     *
     * @param Illuminate\Support\Collection $ctx
     * @param string $type
     * @param mixed $blueprint_class A blank blueprint facade-y sort of thing
     *
     * @return Illuminate\Support\Collection
     */
    private static function getSettingsBlueprintWithValues($ctx, $type, $blueprint_class)
    {
        $settings = AardvarkStorage::getYaml($type, $ctx->get('site'));
        $blueprint = $blueprint_class::requestBlueprint();
        return $blueprint->fields()->addValues($settings)->augment()->values();
    }

    /**
     * Return a meta title for our page
     *
     * @param Illuminate\Support\Collection $data
     * @param Illuminate\Support\Collection $ctx
     *
     * @return mixed
     */
    public static function generatePageTitle($data, $ctx)
    {
        if($data->get('meta_title')->raw()) {
            return $data->get('meta_title');
        }

        $storage = self::getSettingsBlueprintWithValues($ctx, 'general', new GeneralSettingsBlueprint());

        return implode(' ', [
            $data->get('title'),
            $storage->get('title_separator'),
            $storage->get('site_name')
        ]);
    }

    /**
     * Return an image field for use as the OG image on the page
     *
     * @param Illuminate\Support\Collection $data
     * @param Illuminate\Support\Collection $ctx
     *
     * @return Statamic\Fields\Value
     */
    private static function getCalculatedOgImage($data, $ctx)
    {
        $storage = self::getSettingsBlueprintWithValues($ctx, 'social', new SocialSettingsBlueprint());
        return $data->get('og_image')->raw() ? $data->get('og_image') : $storage->get('og_image_site');
    }
}
