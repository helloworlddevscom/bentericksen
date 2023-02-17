<?php

namespace App\Http\Controllers\User;

use App\Business;
use App\Http\Controllers\Controller;
use App\User;
use Bentericksen\ViewAs\ViewAs;
use DB;
use Illuminate\Http\Request;

class ToolsController extends Controller
{
    protected $categories;

    protected $faqs;

    protected $user;

    public function __construct(ViewAs $viewAs)
    {
        $this->user = $viewAs->getUser();

        $this->categories = DB::table('categories')
            ->where('grouping', 'faqs')
            ->get();

        $this->faqs = DB::table('faqs')->get();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function settingsIndex()
    {
        return view('user.tools.settings', [
            'settings' => $this->user->business->getSettings(),
        ]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function settingsSubmit(Request $request)
    {
        $settings = $request->except(['_token', 'business_id']);

        $business = $this->user->business;

        $business->updateSettings($settings);

        return redirect()->back()->with('message', 'Settings updated successfully.');
    }

    public function calculators()
    {
        return view('user.tools.calculator');
    }

    public function faqs()
    {
        $faqs = $this->faqs;

        $categories = $this->categories;

        foreach ($categories as $key => $category) {
            $categories[$key]->faqs = [];

            foreach ($faqs as $faq) {
                if ($faq->category_id == $category->id) {
                    $categories[$key]->faqs[] = $faq;
                }
            }
        }

        return view('user.tools.faqs', compact(['faqs', 'categories']));
    }

    /**
     * Show the matching results returned from search.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return Response
     */
    public function faqSearch(Request $request)
    {
        if (is_null($request->input('keywords') || $request->input('keywords') == '')) {
            return redirect('/user/faqs');
        }

        $term = $request->input('keywords');

        $faqs = DB::table('faqs')
            ->where('question', 'like', '%'.$term.'%')
            ->orWhere('short_answer', 'like', '%'.$term.'%')
            ->orWhere('long_answer', 'like', '%'.$term.'%')
            ->get();

        $categories = $this->categories;

        foreach ($categories as $key => $category) {
            $categories[$key]->faqs = [];

            foreach ($faqs as $faq) {
                if ($faq->category_id == $category->id) {
                    $categories[$key]->faqs[] = $faq;
                }
            }
        }

        return view('user.tools.faqs', compact([
            'faqs',
            'categories',
        ]));
    }
}
