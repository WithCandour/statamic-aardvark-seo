<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP\Redirects;

use Illuminate\Http\Request;
use Statamic\CP\Breadcrumbs;
use Statamic\CP\Column;
use Statamic\Facades\Site;
use Statamic\Support\Str;
use WithCandour\AardvarkSeo\Blueprints\CP\Redirects\RedirectBlueprint;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Controller;
use WithCandour\AardvarkSeo\Redirects\Repositories\RedirectsRepository;

class ManualRedirectsController extends Controller
{
    // Display a list of manual redirects
    public function index()
    {
        $crumbs = Breadcrumbs::make([
            ['text' => 'Aardvark SEO', 'url' => cp_route('aardvark-seo.settings')],
            ['text' => 'Redirects', 'url' => cp_route('aardvark-seo.redirects.manual-redirects.index')],
        ]);

        // Generate columns
        $columns = [
            Column::make('source_url'),
            Column::make('target_url'),
            Column::make('status_code'),
            Column::make('is_active')
        ];

        $redirects = $this->repository()->all()->map(function ($redirect) {
            $edit_url = cp_route('aardvark-seo.redirects.manual-redirects.edit', [
                'manual_redirect' => $redirect['id']
            ]);

            $redirect['edit_url'] = $edit_url;

            return $redirect;
        });

        return view('aardvark-seo::cp.redirects.manual.index', [
            'title' => 'Manual redirects | Aardvark SEO',
            'columns' => $columns,
            'redirects' => $redirects
        ]);
    }

    public function create()
    {
        $fields = $this->blueprint()->fields()->addValues([])->preProcess();
        $crumbs = Breadcrumbs::make([
            ['text' => 'Aardvark SEO', 'url' => cp_route('aardvark-seo.settings')],
            ['text' => 'Redirects', 'url' => cp_route('aardvark-seo.redirects.manual-redirects.index')],
            ['text' => 'Create', 'url' => null],
        ]);

        return view('aardvark-seo::cp.redirects.create', [
            'blueprint' => $this->blueprint()->toPublishArray(),
            'crumbs' => $crumbs,
            'meta' => $fields->meta(),
            'title' => 'Create redirect | Aardvark SEO',
            'values' => $fields->values(),
        ]);
    }

    public function store(Request $request)
    {
        $fields = $this->blueprint()->fields()->addValues($request->all());
        $fields->validate();
        $values = $fields->process()->values()->toArray();
        $values['id'] = Str::uuid()->toString();
        $this->repository()->update($values);
    }

    public function edit(Request $request, string $redirect_id)
    {
        $exists = $this->repository()->exists($redirect_id);

        if(!$exists) {
            return redirect()->route('statamic.cp.aardvark-seo.redirects.manual-redirects.index');
        }

        $existing = $this->repository()->get($redirect_id);

        $fields = $this->blueprint()->fields()->addValues($existing)->preProcess();
        $crumbs = Breadcrumbs::make([
            ['text' => 'Aardvark SEO', 'url' => cp_route('aardvark-seo.settings')],
            ['text' => 'Redirects', 'url' => cp_route('aardvark-seo.redirects.manual-redirects.index')],
            ['text' => __('Edit'), 'url' => null],
        ]);

        return view('aardvark-seo::cp.redirects.edit', [
            'blueprint' => $this->blueprint()->toPublishArray(),
            'crumbs' => $crumbs,
            'meta' => $fields->meta(),
            'title' => 'Edit redirect | Aardvark SEO',
            'values' => $fields->values(),
            'redirect_id' => $redirect_id
        ]);
    }

    public function update(Request $request, string $redirect_id)
    {
        $fields = $this->blueprint()->fields()->addValues($request->all());
        $fields->validate();
        $values = $fields->process()->values()->toArray();
        $this->repository()->update($values, $redirect_id);
    }

    private function blueprint()
    {
        return RedirectBlueprint::requestBlueprint();
    }

    private function repository()
    {
        return new RedirectsRepository('redirects/manual', Site::selected());
    }
}
