<?php

namespace Statamic\Addons\AardvarkSeo\Controllers;

use Statamic\API\Collection;
use Statamic\API\Entry;
use Statamic\API\Page;
use Statamic\API\Taxonomy;
use Statamic\API\Term;

class DefaultsController extends Controller
{
    public function index()
    {
        return $this->view('defaults_index', [
            'title' => 'Content defaults',
            'contentTypes' => $this->getContentTypes(),
        ]);
    }

    private function getContentTypes()
    {
        $pages = [
            'title' => 'Pages',
            'items' => collect([
                [
                    'count' => Page::all()->count(),
                    'slug' => 'pages',
                    'title' => 'Pages'
                ],
            ]),
        ];

        $collections = [
            'title' => 'Collections',
            'items' => Collection::all()->map(function($collection) {
                return [
                    'count' => $collection->count(),
                    'slug' => $collection->path(),
                    'title' => $collection->title(),
                ];
            }),
        ];

        $taxonomies = [
            'title' => 'Taxonomies',
            'items' => Taxonomy::all()->map(function($taxonomy) {
                return [
                    'count' => $taxonomy->count(),
                    'slug' => $taxonomy->path(),
                    'title' => $taxonomy->title(),
                ];
            }),
        ];

        return compact('pages', 'collections', 'taxonomies');
    }
}
