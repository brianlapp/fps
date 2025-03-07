<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use App\Http\Controllers\Controller;
use Inertia\Response;

class MenuController extends Controller
{
    /**
     * Drag & Drop list with menu items.
     *
     * @return Response
     */
    public function index()
    {
        $filters = Request::only(['search']);
        $menus = Menu::query()
            ->orderBy('order')
            ->where('parent_id', 0)
            ->with([
                'children:id,name,link,order,parent_id,deleted_at,is_coming_soon,list,auth_only,is_beta'
            ])
            ->withTrashed()
            ->filter($filters)
            ->get()
            ->transform(function ($menu) {
                return [
                    'id' => $menu->id,
                    'name' => $menu->name,
                    'link' => $menu->link,
                    'order' => $menu->order,
                    'deleted_at' => $menu->deleted_at,
                    'is_coming_soon' => $menu->is_coming_soon,
                    'is_new' => $menu->is_new,
                    'list' => $menu->list,
                    'auth_only' => $menu->auth_only,
                    'is_beta' => $menu->is_beta,
                    'children' => $menu->children()->withTrashed()->get()->toArray()
                ];
            });

        return Inertia::render('Admin/Menu/Index', [
            'menuItems' => $menus,
            'filters' => $filters
        ]);
    }

    /**
     * Show create form.
     *
     * @return Response
     */
    public function create()
    {
        return Inertia::render('Admin/Menu/Edit');
    }

    /**
     * Save menu data.
     *
     * @return RedirectResponse
     */
    public function store()
    {
        Request::validate([
            'name' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string', 'max:255'],
            'is_coming_soon' => ['required', 'boolean'],
            'is_new' => ['required', 'boolean'],
            'auth_only' => ['required', 'boolean'],
            'is_beta' => ['required', 'boolean'],
            'list' => ['required', 'string'],
        ]);

        Menu::create(Request::only(['name', 'link', 'is_coming_soon', 'is_new' ,'list', 'auth_only', 'is_beta']));

        $this->resetMenu(false);

        return Redirect::route('admin.menu.index')->with('message', 'Menu created.');
    }

    /**
     * Edit form for menu item.
     *
     * @param Menu $menu
     * @return Response
     */
    public function edit(Menu $menu)
    {
        return Inertia::render('Admin/Menu/Edit', [
            'menu' => [
                'id' => $menu->id,
                'name' => $menu->name,
                'link' => $menu->link,
                'deleted_at' => $menu->deleted_at,
                'is_coming_soon' => $menu->is_coming_soon,
                'is_new' => $menu->is_new,
                'auth_only' => $menu->auth_only,
                'is_beta' => $menu->is_beta,
                'list' => $menu->list,
            ]
        ]);
    }

    /**
     * Update data for item menu.
     *
     * @param Menu $menu
     * @return RedirectResponse
     */
    public function update(Menu $menu)
    {
        Request::validate([
            'name' => ['required', 'string', 'max:255'],
            'link' => ['required', 'string', 'max:255'],
            'is_coming_soon' => ['required', 'boolean'],
            'is_new' => ['required', 'boolean'],
            'auth_only' => ['required', 'boolean'],
            'is_beta' => ['required', 'boolean'],
            'list' => ['required', 'string'],
        ]);

        $menu->update(Request::only(['name', 'link', 'is_coming_soon', 'is_new', 'list', 'auth_only', 'is_beta']));

        $this->resetMenu(false);

        return Redirect::back()->with('message', 'Menu updated.');
    }

    /**
     * Soft delete for menu item.
     *
     * @param Menu $menu
     * @return RedirectResponse
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();
        Menu::query()->where('parent_id', $menu->id)->delete();
        $this->resetMenu(false);

        return Redirect::back()->with('message', 'Menu deleted.');
    }

    /**
     * Restore menu item.
     *
     * @param Menu $menu
     * @return RedirectResponse
     */
    public function restore(Menu $menu)
    {
        $menu->restore();
        Menu::query()->where('parent_id', $menu->id)->restore();
        $this->resetMenu(false);
        $menu->refresh();

        return Redirect::back()->with('message', 'Menu restored.');
    }

    /**
     * Change menu items.
     *
     * @return JsonResponse
     */
    public function changeMenu()
    {
        $this->updateMenuItems(Request::get('items'));
        $this->resetMenu(false);

        return response()->json([
            'status' => 'ok'
        ]);
    }

    /**
     * Recursive sorting menu && updating.
     *
     * @param $items
     * @param int $parentId
     * @param int $parentOrder
     */
    protected function updateMenuItems($items, $parentId = 0, $parentOrder = 0)
    {
        foreach ($items as $item) {
            Menu::where('id', $item['id'])->update([
                'parent_id' => $parentId,
                'order' => ++$parentOrder,
                'list' => $item['list']
            ]);

            if (!empty($item['children'])) {
                $this->updateMenuItems($item['children'], $item['id']);
            }
        }
    }

    /**
     * Reset cached menu.
     *
     * @param bool $redirect
     * @return JsonResponse|RedirectResponse
     */
    public function resetMenu(bool $redirect = true): JsonResponse|RedirectResponse
    {
        Menu::getCached(true);

        return $redirect ? Redirect::back()->with('message', 'Cache with menu was refreshed.') : response()->json(['message' => true]);
    }
}
