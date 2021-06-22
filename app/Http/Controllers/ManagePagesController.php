<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use App\Http\Requests\PagesCreateRequest;
use Illuminate\Support\Facades\Auth;
use App\Scheme;
use App\Certification;
use App\Http\Requests\CertificationRequest;

class ManagePagesController extends Controller
{
    public function index()
    {
        if(Auth::user()->can('read', Page::class)) {
            $pages = Page::all();
            return view('manage.pages.index', compact('pages'));
        } else {
            return view('errors.403');
        }
    }

    public function create()
    {
        if(Auth::user()->can('create', Page::class)) {
            return view('manage.pages.create');

        } else {
            return view('errors.403');
        }
    }

    public function store(PagesCreateRequest $request)
    {
        if(Auth::user()->can('create', Page::class)) {
            
            $userInput = $request->all();
             
            $input["type"] = $userInput["type"];
            $input["title"] = $userInput["title"];
            $input["content"] = $userInput["content"];
            // $input["priority"] = $userInput["priority"];
            // $input["show_in_menu"] = $request->show_in_menu ? true : false;
            // $input["show_in_footer"] = $request->show_in_footer ? true : false;
            $input["meta_title"] = $userInput["meta_title"];
            $input["meta_desc"] = $userInput["meta_desc"];
            $input["meta_keywords"] = $userInput["meta_keywords"];

            if($request->status == 1) {
                $input['is_active'] = $request->status;
            } else {
                $input['is_active'] = 0;
            }

            $page = new Page($input);
            $page->save();

            session()->flash('page_created', __("New page has been added."));
            session()->flash('page_view', $page->slug);
            return redirect(route('manage.pages.index'));
        } else {
            return view('errors.403');
        }
    }

    public function edit($id)
    {
        if(Auth::user()->can('update', Page::class)) {
            $page = Page::where('id', $id)->firstOrFail();
            return view('manage.pages.edit', compact('page'));
        } else {
            return view('errors.403');
        }
    }

    public function update(PagesCreateRequest $request, $id)
    {
           
        if(Auth::user()->can('update', Page::class)) {
            $page = Page::findOrFail($id);
            $userInput = $request->all();
            
            $input["type"] = $userInput["type"];
            $input["title"] = $userInput["title"];
            $input["content"] = $userInput["content"];
            // $input["priority"] = $userInput["priority"];
            // $input["show_in_menu"] = $request->show_in_menu ? true : false;
            // $input["show_in_footer"] = $request->show_in_footer ? true : false;
            $input["meta_title"] = $userInput["meta_title"];
            $input["meta_desc"] = $userInput["meta_desc"];
            $input["meta_keywords"] = $userInput["meta_keywords"];

            if($request->status == 1) {
                $input['is_active'] = $request->status;
            } else {
                $input['is_active'] = 0;
            }

            $page->update($input);

            session()->flash('page_updated', __("The page has been updated."));
            return redirect(route('manage.pages.edit', $page->id));
        } else {
            return view('errors.403');
        }
    }

    public function destroy($id)
    {
        if(Auth::user()->can('delete', Page::class)) {
            $page = Page::findOrFail($id);
            $page->delete();
            session()->flash('page_deleted', __("The page has been deleted."));
            return redirect(route('manage.pages.index'));
        } else {
            return view('errors.403');
        }
    }

    public function deletePages(Request $request)
    {
        if(Auth::user()->can('delete', Page::class)) {
            if(isset($request->delete_single)) {
                $page = Page::findOrFail($request->delete_single);
                $page->delete();
                session()->flash('page_deleted', __("The page has been deleted."));
            } else {
                if(isset($request->delete_all) && !empty($request->checkboxArray)) {
                    $pages = Page::findOrFail($request->checkboxArray);
                    foreach($pages as $page) {
                        $page->delete();
                    }
                    session()->flash('page_deleted', __("The selected pages have been deleted."));
                } else {
                    session()->flash('page_not_deleted', __("Please select pages to be deleted."));
                }
            }
            return redirect(route('manage.pages.index'));
        } else {
            return view('errors.403');
        }
    }
    public function Schemes() {
        $scheme = Scheme::get()->first();
         
        return view('manage.pages.schemes',compact('scheme'));
    }
    
    public function saveScheme(Request $request) {
        Scheme::create($request->all());
       session()->flash('page_created', __("Scheme has been added."));
       
       return redirect(route('manage.pages.schemes'));
    }
    public function updateScheme(Request $request, $id) {
            $scheme = Scheme::findOrFail($id);
            $userInput = $request->all();
            $scheme->update($userInput);
            session()->flash('page_updated', __("Scheme has been updated."));
            return redirect(route('manage.pages.schemes'));

    }

    public function Certifications() {
        
        $certifications = Certification::get()->first();
        return view('manage.pages.certifications',compact('certifications'));
    }

    public function saveCertifications(CertificationRequest $CertificationRequest) {
        Certification::create($CertificationRequest->all());
       session()->flash('page_created', __("Certification has been added."));
       
       return redirect(route('manage.pages.certifications'));
    }

    public function updateCertifications(CertificationRequest $CertificationRequest, $id) {
        $certification = Certification::findOrFail($id);
        $userInput = $CertificationRequest->all();
        $certification->update($userInput);
        session()->flash('page_updated', __("Certification has been updated."));
        return redirect(route('manage.pages.certifications'));

    }
}
