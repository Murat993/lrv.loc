<?php

namespace App\Http\Controllers\Ajax;


use App\Entity\Regions;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function get(Request $request) : array
    {
        $parent = $request->get('parent') ?: null;

        return Regions::where('parent_id', $parent)
            ->orderBy('name')
            ->select('id', 'name')
            ->get()
            ->toArray();
    }
}
