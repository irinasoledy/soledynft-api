<?php

namespace App\Http\Controllers\Admin;
use App\Base as Model;
use App\Http\Controllers\Controller;
use Admin\Http\Controllers\GoogleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Models\StaticPage;
use Revolution\Google\Sheets\Facades\Sheets;


class StaticPagesController extends Controller
{
    public function googleApisynchronize()
    {
        ini_set('memory_limit', '-1');
        ini_set('max_execution_time', 900);

        $postSpreadsheetId = '1oRqBaF5skk-1igfl9lWRqRLn2WoJZxlCqME8dygp1Rsu9zlRG5X-6So';
        $sheetIds = Sheets::spreadsheet($postSpreadsheetId)->sheetList();

        foreach ($sheetIds as $key => $sheetId) {
            if ($key != '1603993476') {
                $parsedBody = '';

                $sheets = Sheets::spreadsheet($postSpreadsheetId)
                               ->sheetById($key)
                               ->all();
               $googleHelper = new GoogleController();
               $sheets = $googleHelper->parseSheet($sheets);

               $page = StaticPage::where('alias', $sheetId)->first();

               if (is_null($page)) {
                   $page = StaticPage::create([ 'alias' => $sheetId]);
               }

               $page->translations()->delete();

               foreach ($this->langs as $key => $lang) {
                    $parsedBody = '';
                    Model::$lang = $lang->id;
                    \App::setLocale($lang->lang);

                    foreach ($sheets as $key => $sheet) {
                       $parsedBody .= $sheet['OpenTag'];
                       $parsedBody .= trans('vars.'.$sheet['Texts']);
                       $parsedBody .= $sheet['CloseTag'];
                    }
                    $page->translations()->create([
                      'lang_id' => $lang->id,
                      'name' => $sheetId,
                      'body' => $parsedBody,
                    ]);
               }
            }
        }
        return redirect()->back();
    }


    public function index()
    {
        $pages = StaticPage::orderBy('created_at', 'desc')->get();

        return view('admin.static_pages.index', compact('pages'));
    }

    public function create()
    {
        return view('admin.static_pages.create');
    }

    public function store(Request $request)
    {
        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $page = new StaticPage();
        $page->alias = str_slug(request('title_'.$this->lang->lang));
        $page->active = 1;
        $page->save();

        foreach ($this->langs as $lang):
            $page->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang)
            ]);
        endforeach;

        Session::flash('message', 'New item has been created!');
        return redirect()->route('static-pages.index');
    }

    public function edit($id)
    {
        $page = StaticPage::findOrFail($id);

        return view('admin.static_pages.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $toValidate['title_'.$this->lang->lang] = 'required|max:255';
        $validator = $this->validate($request, $toValidate);

        $page = StaticPage::findOrFail($id);

        $page->translations()->delete();

        foreach ($this->langs as $lang):
            $page->translations()->create([
                'lang_id' => $lang->id,
                'name' => request('title_' . $lang->lang),
                'body' => request('body_' . $lang->lang),
                'meta_title' => request('meta_title_' . $lang->lang),
                'meta_keywords' => request('meta_keywords_' . $lang->lang),
                'meta_description' => request('meta_description_' . $lang->lang)
            ]);
        endforeach;

        return redirect()->back();
    }

    public function status($id)
    {
        $page = Page::findOrFail($id);

        if ($page->active == 1) {
            $page->active = 0;
        } else {
            $page->active = 1;
        }

        $page->save();

        return redirect()->route('pages.index');
    }

    public function destroy($id)
    {
        $page = StaticPage::findOrFail($id);
        $page->delete();

        session()->flash('message', 'Item has been deleted!');
        return redirect()->route('static-pages.index');
    }
}
