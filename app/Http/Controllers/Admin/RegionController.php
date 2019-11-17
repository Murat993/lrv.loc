<?php

namespace App\Http\Controllers\Admin;
use App\Entity\Regions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
class RegionController extends Controller
{
    public function index()
    {
        $regions = Regions::where('parent_id', null)->orderBy('name')->get();

        return view('admin.regions.index', compact('regions'));
    }

    public function create(Request $request)
    {
        $parent = null;
        if ($request->get('parent')) {
            $parent = Regions::findOrFail($request->get('parent'));
        }

        return view('admin.regions.create', compact('parent'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:regions,name,NULL,id,parent_id,' . ($request['parent'] ?: 'NULL'),
            'slug' => 'required|string|max:255|unique:regions,slug,NULL,id,parent_id,' . ($request['parent'] ?: 'NULL'),
            'parent' => 'nullable|exists:regions,id',
        ]);

        $regions = Regions::create([
            'name' => $request['name'],
            'slug' => $request['slug'],
            'parent_id' => $request['parent'],
        ]);

        return redirect()->route('admin.regions.show', $request['parent']);
    }

    public function show(Regions $region)
    {
        $regions = Regions::where('parent_id', $region->id)->orderBy('name')->get();

        return view('admin.regions.show', compact('region', 'regions'));
    }

    public function edit(Regions $region)
    {
        return view('admin.regions.edit', compact('region'));
    }

    public function update(Request $request, Regions $region)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255|unique:regions,name,' . $region->id . ',id,parent_id,' . $region->parent_id,
            'slug' => 'required|string|max:255|unique:regions,slug,' . $region->id . ',id,parent_id,' . $region->parent_id,
        ]);

        $region->update([
            'name' => $request['name'],
            'slug' => $request['slug'],
        ]);

        return redirect()->route('admin.regions.show', $region);
    }
    public function destroy(Regions $region)
    {
        $region->delete();

        return redirect()->route('admin.regions.index');
    }
}
