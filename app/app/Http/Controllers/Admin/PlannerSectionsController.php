<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PlannerSectionRequest;
use App\Models\Admin;
use App\Models\PlannerCategory;
use App\Models\PlannerSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\PlannerSection as PlannerSectionResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PlannerSectionsController
{
    private Admin $admin;

    public function __construct()
    {
        $this->admin = Auth::guard('admin')->user();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function index(PlannerCategory $plannerCategory, Request $request): Response
    {
        $filters = $request->only(['search', 'trashed', 'isActive']);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = PlannerSection::query()->where('planner_category_id', $plannerCategory->id)->filter($filters)->orderBy('order', 'ASC')->paginate($request->get('per_page', 100));
        $list->setCollection(PlannerSectionResource::collection($list->getCollection())->collection);
        $category = new \App\Http\Resources\Admin\PlannerCategory($plannerCategory);
        return Inertia::render('Admin/PlannerSection/Index', compact('list', 'filters', 'category'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(PlannerCategory $plannerCategory, Request $request): Response
    {
        $category = new \App\Http\Resources\Admin\PlannerCategory($plannerCategory);
        return Inertia::render('Admin/PlannerSection/Edit', compact('category'));
    }

    public function store(PlannerSectionRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('createPlannerSection')) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }
        $plannerSection = new PlannerSection();
        $data = $request->validated();
        $items = $data['items'];
        unset($data['items']);
        $plannerSection->fill($data);
        $latestByOrder = PlannerSection::query()->where('planner_category_id', $plannerSection->planner_category_id)->orderBy('order', 'DESC')->first();
        $plannerSection->order = !empty($latestByOrder) ? $latestByOrder->order + 1 : 1;
        $plannerSection->save();
        $plannerSection->refresh();
        $plannerSection->items = $items;

        $plannerSection->refresh();

        $plannerSection->updateImage($request->get('image'));

        return Redirect::route('admin.planner_sections.index', $plannerSection->planner_category_id)->with('message', 'Planner Section Created');
    }

    public function edit(PlannerCategory $plannerCategory, PlannerSection $plannerSection, Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $plannerSection)) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }

        return Inertia::render('Admin/PlannerSection/Edit', [
            'section' => new PlannerSectionResource($plannerSection),
            'category' =>  new \App\Http\Resources\Admin\PlannerCategory($plannerCategory)
        ]);
    }

    public function update(PlannerSection $plannerSection, PlannerSectionRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $plannerSection)) {
            return \redirect()->route('admin.planner_categories')->with('error', 'Access Denied');
        }
        $data = $request->validated();

        $plannerSection->fill($data);
        $plannerSection->save();
        $plannerSection->updateImage($request->get('image'));

//        return Redirect::route('admin.planner_sections.index')->with('message', 'Planner Section Updated');
        return Redirect::back()->with('message', 'Planner Section Updated');
    }

    public function destroy(PlannerSection $plannerSection): RedirectResponse
    {
        if ($this->admin->cannot('delete', $plannerSection)) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }
        $plannerSection->delete();

        return \redirect()->back()->with('message', 'Planner Section Deleted');
    }

    public function recover(PlannerSection $plannerSection): RedirectResponse
    {
        if ($this->admin->cannot('delete', $plannerSection)) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }
        $plannerSection->restore();

        return \redirect()->back()->with('message', 'Planner Section Recovered');
    }

    /**
     * Change sections order.
     *
     * @return JsonResponse
     */
    public function reorder(Request $request)
    {
        $items = $request->get('items');
        foreach ($items as $index => $item) {
            $model = PlannerSection::find($item['id']);
            if (!empty($model)) {
                $model->order = $index + 1;
                $model->save();
            }
        }

        return response()->json([
            'status' => 'ok'
        ]);
    }
}
