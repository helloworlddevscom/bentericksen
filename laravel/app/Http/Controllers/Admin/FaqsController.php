<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Faq;
use App\Http\Controllers\Controller;
use Bentericksen\Settings\Industries;
use Bentericksen\Settings\States;
use DB;
use Illuminate\Http\Request;

class FaqsController extends Controller
{
    /**
     * @var Category
     */
    private $categories;

    /**
     * @var Faq
     */
    private $faqs;

    /**
     * @var array
     */
    private $states;

    /**
     * @var array
     */
    private $industries;
    /**
     * @var Faq
     */
    private $faq;
    /**
     * @var Category
     */
    private $category;

    public function __construct(
        Faq $faq,
        Category $category
    ) {
        $this->states = (new States)->states();
        $this->industries = (new Industries)->getIndustries();
        $this->faq = $faq;
        $this->category = $category;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $faqs = $this->faq->all();

        $categories = [];

        foreach ($this->getCategories() as $category) {
            $cat = $category->toArray();

            $cat['faqs'] = [];

            foreach ($faqs as $faq) {
                if ($faq->category_id == $category->id) {
                    array_push($cat['faqs'], $faq);
                }
            }

            array_push($categories, $cat);
        }

        return view('admin.faq.index', compact(['faqs', 'categories']));
    }

    /**
     * Show the matching results returned from search.
     *
     * @param Request $request
     * @return Response
     */
    public function indexSearch(Request $request)
    {
        if (is_null($request->input('keywords') || $request->input('keywords') == '')) {
            return redirect('/admin/faqs');
        }

        $term = $request->input('keywords');

        $faqs = $this->faq->where('question', 'like', '%'.$term.'%')
                ->orWhere('short_answer', 'like', '%'.$term.'%')
                ->orWhere('long_answer', 'like', '%'.$term.'%')
                ->get();

        $categories = [];

        foreach ($this->getCategories() as $category) {
            $cat = $category->toArray();

            $cat['faqs'] = [];

            foreach ($faqs as $faq) {
                if ($faq->category_id == $category->id) {
                    array_push($cat['faqs'], $faq);
                }
            }

            array_push($categories, $cat);
        }

        return view('admin.faq.index', compact(['collection', 'faqs', 'categories']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = $this->getCategories();
        $states = $this->states;
        $industries = $this->industries;

        return view('admin.faq.create', compact(['categories', 'states', 'industries']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = [
            'question'      => $request->input('question'),
            'short_answer'  => $request->input('short_answer'),
            'long_answer'   => $request->input('long_answer'),
            'category_id'   => $request->input('category') === '' ? 11 : $request->input('category'),
            'state'         => $request->input('state'),
            'business_type' => $request->input('business_type'),
        ];

        $this->faq->insert($data);

        return redirect('/admin/faqs');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $categories = $this->getCategories();
        $states = $this->states;
        $industries = $this->industries;
        $faq = $this->faq->find($id);

        return view('admin.faq.edit', compact(['faq', 'categories', 'states', 'industries']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $data = [
            'question'      => $request->input('question'),
            'short_answer'  => $request->input('short_answer'),
            'long_answer'   => $request->input('long_answer'),
            'category_id'   => $request->input('category'),
            'state'         => $request->input('state'),
            'business_type' => $request->input('business_type'),
        ];

        $faq = $this->faq->find($id);

        $faq->update($data);

        return redirect('/admin/faqs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        $this->faq->find($id)->delete();

        return redirect('/admin/faqs');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function categoryCreate(Request $request)
    {
        $this->category->create([
            'name' => $request->input('name'),
            'business_id' => 0,
            'grouping' => 'faqs',
        ]);

        return redirect('/admin/faqs');
    }

    /**
     * Returns FAQ Categories.
     *
     * @return Category
     */
    private function getCategories()
    {
        if (! $this->categories) {
            $this->categories = $this->category->where('grouping', 'faqs')->get();
        }

        return $this->categories;
    }
}
