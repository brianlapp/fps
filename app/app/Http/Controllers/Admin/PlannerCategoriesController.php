<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\PlannerCategoryRequest;
use App\Models\Admin;
use App\Models\PlannerCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\PlannerCategory as PlannerCategoryResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class PlannerCategoriesController
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
    public function index(Request $request): Response
    {
        $filters = $request->only(['search', 'trashed', 'isActive']);
        /**
         * @var $list LengthAwarePaginator
         */
        $list = PlannerCategory::query()->filter($filters)->orderBy('order', 'ASC')->paginate($request->get('per_page', 100));
        $list->setCollection(PlannerCategoryResource::collection($list->getCollection())->collection);

        return Inertia::render('Admin/PlannerCategory/Index', compact('list', 'filters'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Admin/PlannerCategory/Edit', []);
    }

    public function store(PlannerCategoryRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('createPlannerCategory')) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }
        $plannerCategory = new PlannerCategory();
        $data = $request->validated();
        $plannerCategory->fill($data);
        $latestByOrder = PlannerCategory::query()->orderBy('order', 'DESC')->first();
        $plannerCategory->order = !empty($latestByOrder) ? $latestByOrder->order + 1 : 1;
        $plannerCategory->save();

        $plannerCategory->refresh();

        $plannerCategory->updateImage($request->get('image'));

        return Redirect::route('admin.planner_categories.index')->with('message', 'Planner Category Created');
    }

    public function edit(PlannerCategory $plannerCategory, Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $plannerCategory)) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }

        return Inertia::render('Admin/PlannerCategory/Edit', [
            'category' => new PlannerCategoryResource($plannerCategory),
        ]);
    }

    public function update(PlannerCategory $plannerCategory, PlannerCategoryRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $plannerCategory)) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }
        $data = $request->validated();

        $plannerCategory->fill($data);
        $plannerCategory->save();
        $plannerCategory->updateImage($request->get('image'));

//        return Redirect::route('admin.planner_categories.index')->with('message', 'Planner Category Updated');
        return Redirect::back()->with('message', 'Planner Category Updated');
    }

    public function destroy(PlannerCategory $plannerCategory): RedirectResponse
    {
        if ($this->admin->cannot('delete', $plannerCategory)) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }
        $plannerCategory->delete();

        return \redirect()->back()->with('message', 'Planner Category Deleted');
    }

    public function recover(PlannerCategory $plannerCategory): RedirectResponse
    {
        if ($this->admin->cannot('delete', $plannerCategory)) {
            return \redirect()->route('admin.planner_categories.index')->with('error', 'Access Denied');
        }
        $plannerCategory->restore();

        return \redirect()->back()->with('message', 'Planner Category Recovered');
    }

    /**
     * Change categories order.
     *
     * @return JsonResponse
     */
    public function reorder(Request $request)
    {
        $items = $request->get('items');
        foreach ($items as $index => $item) {
            $model = PlannerCategory::find($item['id']);
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
