<?php

namespace WithCandour\AardvarkSeo\Http\Controllers\CP\Redirects;

use Illuminate\Http\Request;
use Statamic\CP\Breadcrumbs;
use Statamic\CP\Column;
use Statamic\Facades\Action;
use Statamic\Facades\Site;
use WithCandour\AardvarkSeo\Actions\Redirects\DeleteAutoRedirectsAction;
use WithCandour\AardvarkSeo\Blueprints\CP\Redirects\RedirectBlueprint;
use WithCandour\AardvarkSeo\Http\Controllers\CP\Controller;
use WithCandour\AardvarkSeo\Redirects\Repositories\RedirectsRepository;

class AutoRedirectsController extends Controller
{
    /**
     * Display a list of auto redirects in a table with actions
     */
    public function index()
    {
        $this->authorize('view aardvark redirects');

        $crumbs = Breadcrumbs::make([
            ['text' => 'Aardvark SEO', 'url' => cp_route('aardvark-seo.settings')],
            ['text' => __('aardvark-seo::redirects.plural'), 'url' => cp_route('aardvark-seo.redirects.auto-redirects.index')],
        ]);

        // Generate columns
        $columns = [
            Column::make('source_url')->label(__('aardvark-seo::redirects.redirect.source_url')),
            Column::make('target_url')->label(__('aardvark-seo::redirects.redirect.target_url')),
            Column::make('status_code')->label(__('aardvark-seo::redirects.redirect.status_code')),
            Column::make('is_active')->label(__('aardvark-seo::redirects.redirect.is_active'))
        ];

        $redirects = $this->repository()->all()->map(function ($redirect) {

            $delete_url = cp_route('aardvark-seo.redirects.auto-redirects.destroy', [
                'auto_redirect' => $redirect['id']
            ]);

            $redirect['delete_url'] = $delete_url;
            $redirect['title'] = $redirect['source_url'];

            return $redirect;
        });

        return view('aardvark-seo::cp.redirects.auto.index', [
            'title' => __('aardvark-seo::redirects.pages.auto'),
            'columns' => $columns,
            'redirects' => $redirects,
            'auto_redirects_enabled' => config('aardvark-seo.auto_redirects_enabled'),
            'crumbs' => $crumbs
        ]);
    }

    /**
     * Delete an existing redirect
     *
     * @param Request $request
     * @param string $redirect_id
     */
    public function destroy(Request $request, string $redirect_id)
    {
        $this->authorize('edit aardvark redirects');

        return $this->repository()->delete($redirect_id);
    }

    /**
     * Return the bulk actions for the redirects table
     *
     * @param Request $request
     */
    public function bulkActions(Request $request)
    {
        return collect([new DeleteAutoRedirectsAction()]);
    }

    /**
     * Run actions from request
     *
     * @param Request $request
     */
    public function runActions(Request $request)
    {
        $this->authorize('edit aardvark redirects');

        $data = $request->validate([
            'action' => 'required',
            'selections' => 'required|array',
            'context' => 'sometimes',
        ]);

        $context = $data['context'] ?? [];

        $action = Action::get($request->action)->context($context);

        // die(print_r($data['selections']));

        $redirects = collect($data['selections']);

        $action->run($redirects, $request->all());
    }

    /**
     * Return the blueprint
     */
    private function blueprint()
    {
        return RedirectBlueprint::requestBlueprint();
    }

    /**
     * Return a redirects repository
     */
    private function repository()
    {
        return new RedirectsRepository('redirects/auto', Site::selected());
    }
}
