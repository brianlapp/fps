<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Admin\TopicRequest;
use App\Models\Admin;
use App\Models\Topic;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use \App\Http\Resources\Admin\Topic as TopicResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class TopicsController
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
        $list = Topic::query()->filter($filters)->latest()->paginate($request->get('per_page', 20));
        $list->setCollection(TopicResource::collection($list->getCollection())->collection);

        return Inertia::render('Admin/Topic/Index', compact('list', 'filters'));
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Admin/Topic/Edit', []);
    }

    public function store(TopicRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('createTopic')) {
            return \redirect()->route('admin.topics.index')->with('error', 'Access Denied');
        }
        $topic  = new Topic();
        $data = $request->validated();

        $topic ->fill($data);
        $topic ->save();
        $topic ->refresh();

        $topic ->updateImage($request->get('image'));

        return Redirect::route('admin.topics.index')->with('message', 'Topic  Created');
    }

    public function edit(Topic $topic , Request $request): RedirectResponse|Response
    {
        if ($this->admin->cannot('update', $topic )) {
            return \redirect()->route('admin.topics.index')->with('error', 'Access Denied');
        }

        return Inertia::render('Admin/Topic/Edit', [
            'topic' => new TopicResource($topic ),
        ]);
    }

    public function update(Topic $topic , TopicRequest $request): RedirectResponse
    {
        if ($this->admin->cannot('update', $topic )) {
            return \redirect()->route('admin.topics.index')->with('error', 'Access Denied');
        }
        $data = $request->validated();

        $topic ->fill($data);
        $topic ->save();
        $topic ->updateImage($request->get('image'));

        return Redirect::route('admin.topics.index')->with('message', 'Topic  Updated');
    }

    public function destroy(Topic $topic ): RedirectResponse
    {
        if ($this->admin->cannot('delete', $topic )) {
            return \redirect()->route('admin.topics.index')->with('error', 'Access Denied');
        }
        $topic ->delete();

        return \redirect()->back()->with('message', 'Topic  Deleted');
    }

    public function recover(Topic $topic ): RedirectResponse
    {
        if ($this->admin->cannot('delete', $topic )) {
            return \redirect()->route('admin.topics.index')->with('error', 'Access Denied');
        }
        $topic ->restore();

        return \redirect()->back()->with('message', 'Topic  Recovered');
    }
}
