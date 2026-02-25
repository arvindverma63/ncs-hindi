<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Language;
use App\Repositories\Contracts\PageRepositoryInterface;
use Illuminate\Http\Request;

class PageController extends Controller
{
    private PageRepositoryInterface $pageRepository;

    private array $pageTypes = [
        'privacy' => 'Privacy Policy',
        'terms' => 'Terms & Conditions',
    ];

    public function __construct(PageRepositoryInterface $pageRepository)
    {
        $this->pageRepository = $pageRepository;
    }

    public function index()
    {
        $pages = $this->pageTypes;
        return view('admin.pages.index', compact('pages'));
    }

    public function edit(Request $request, string $type)
    {
        if (!array_key_exists($type, $this->pageTypes)) {
            abort(404, 'Invalid page type');
        }

        $page = $this->pageRepository->getByType($type, $currentLanguage->id ?? null);

        return view('admin.pages.edit', compact('page', 'type'));
    }

    public function update(UpdatePageRequest $request, string $type)
    {
        try {
            if (!array_key_exists($type, $this->pageTypes)) {
                abort(404, 'Invalid page type');
            }

            $data = $request->validated();

            $this->pageRepository->updateOrCreate($type, $data);

            return redirect()
                ->route('admin.pages.edit', ['type' => $type]);
        } catch (\Exception $e) {
            $notification = ['messege' => 'Failed to update page: ' . $e->getMessage(), 'alert-type' => 'error'];
            return redirect()->back()->with($notification);
        }
    }
}
