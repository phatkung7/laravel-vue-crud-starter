<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Requests\Kpis\KpisRequest;
use App\Models\Kpi;
use App\Models\Tag;
use Illuminate\Http\Request;
use App\Models\ApproveStatus;

class KpiController extends BaseController
{

    protected $kpi = '';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Kpi $kpi)
    {
        $this->middleware('auth:api');
        $this->kpi = $kpi;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $kpis = $this->kpi->with('ApproveStatus')->OrderBy('updated_at','desc')->paginate(10);
        // dd($kpis);
        //return $this->sendResponse($kpis, 'Kpis list');
        return response()->json(array("data" => $kpis));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  App\Http\Requests\Products\ProductRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KpisRequest $request)
    {
        $kpis = $this->kpi->create([
            'kpi_no' => $request->get('kpi_no'),
            'kpi_title' => $request->get('kpi_title'),
            'approve_status_id' => $request->get('approve_status_id'),
            'budget_year' => $request->get('budget_year'),
        ]);

        // update pivot table
        // $tag_ids = [];
        // foreach ($request->get('tags') as $tag) {
        //     $existingtag = Tag::whereName($tag['text'])->first();
        //     if ($existingtag) {
        //         $tag_ids[] = $existingtag->id;
        //     } else {
        //         $newtag = Tag::create([
        //             'name' => $tag['text']
        //         ]);
        //         $tag_ids[] = $newtag->id;
        //     }
        // }
        // $product->tags()->sync($tag_ids);

        return $this->sendResponse($kpis, 'Kpi Created Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $kpis = $this->kpi->with('ApproveStatus')->findOrFail($id);

        return $this->sendResponse($kpis, 'Kpi Details');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(KpisRequest $request, $id)
    {
        $kpis = $this->kpi->findOrFail($id);

        $kpis->update($request->all());

        // update pivot table
        // $tag_ids = [];
        // foreach ($request->get('tags') as $tag) {
        //     $existingtag = Tag::whereName($tag['text'])->first();
        //     if ($existingtag) {
        //         $tag_ids[] = $existingtag->id;
        //     } else {
        //         $newtag = Tag::create([
        //             'name' => $tag['text']
        //         ]);
        //         $tag_ids[] = $newtag->id;
        //     }
        // }
        // $product->tags()->sync($tag_ids);

        return $this->sendResponse($kpis, 'Kpi Information has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $this->authorize('isAdmin');

        $product = $this->product->findOrFail($id);

        $product->delete();

        return $this->sendResponse($product, 'Product has been Deleted');
    }

    public function upload(Request $request)
    {
        $fileName = time() . '.' . $request->file->getClientOriginalExtension();
        $request->file->move(public_path('upload'), $fileName);

        return response()->json(['success' => true]);
    }

    public function list()
    {
        $categories = $this->category->pluck('name', 'id');

        return $this->sendResponse($categories, 'Category list');
    }
}
