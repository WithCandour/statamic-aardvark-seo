<?php

namespace Statamic\Addons\AardvarkSeo\Traits;

trait TransformsAssetsFieldtypes
{
    /**
     * Replace the asset fields container in a fieldset with the one set in
     * the addon settings.
     *
     * @param array  $fields    The fieldset containing fields to transform
     * @param string $container The name of the target container
     *
     * @return array $fieldsArray The transformed fields
     */
    protected function transformAssetsFields($fields, $container)
    {
        $transformedFields = collect($fields)->map(function ($field, $key) use ($container) {
            if ($field['type'] === 'assets') {
                $field['container'] = $container;
            }
            return $field;
        });

        $fieldsArray = $transformedFields->toArray();

        return $fieldsArray;
    }
}
