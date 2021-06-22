<?php

namespace App\Http\Controllers;

use App\Category;
use App\Location;
use App\Photo;
use App\SpecificationType;
use App\Http\Requests\CategoriesCreateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManageCategoriesController extends Controller
{
    public function index()
    {
        if(Auth::user()->can('read', Category::class)) {
            $location_id = Auth::user()->location_id;
            if(request()->has('level') && request()->level == 'root') {
                 
                $categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
            } else if(request()->has('status') && request()->status == 'active') {
                
                $categories = Category::where('location_id', $location_id)->where('category_id', 1)->get();
            } else {
                 
                $categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
                
            }
            $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
            $specification_types = SpecificationType::where('location_id', Auth::user()->location_id)->get();
             
            return view('manage.categories.index', compact('categories', 'root_categories', 'specification_types'));
        } else {
            return view('errors.403');
        }
    }

    public function create() {
        $location_id = Auth::user()->location_id;
        $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
        return view('manage.categories.create',compact('root_categories'));
    }
    public function store(CategoriesCreateRequest $request)
    {
         
         
        $request->merge(['location' => Auth::user()->location_id]);
        if(Auth::user()->can('create', Category::class)) {
            $this->validate($request, [
                'name' => 'unique_with:categories,location=location_id,parent=category_id'
            ]);
            $userInput = $request->all();
            $input["name"] = $userInput["name"];
            $input["parent"] = $userInput["parent"];
            if(array_key_exists('specification_type', $userInput)) {
                $input["specification_type"] = $userInput["specification_type"];
            }
            $input["priority"] = isset($userInput["priority"]) ? $userInput["priority"] : 1;
            $input["top_category_priority"] = isset($userInput["top_category_priority"]) ? $userInput["top_category_priority"] : null;
            $input["show_in_menu"] = $request->show_in_menu ? true : false;
            $input["show_in_footer"] = $request->show_in_footer ? true : false;
            $input["show_in_slider"] = $request->show_in_slider ? true : false;
            
            $input["show_filter_price"] = $request->show_filter_price ? true : false;
            $input["show_filter_metal"] = $request->show_filter_metal ? true : false;
            
            $input["show_filter_purity"] = $request->show_filter_purity ? true : false;
            $input["show_filter_gender"] = $request->show_filter_gender ? true : false;
            $input["show_filter_offers"] = $request->show_filter_offers ? true : false;
            $input["show_filter_short_by"] = $request->show_filter_short_by ? true : false;
            
            $input["meta_title"] = $userInput["meta_title"];
            $input["meta_desc"] = $userInput["meta_desc"];
            $input["meta_keywords"] = $userInput["meta_keywords"];
            $input["min_price"] = $userInput["min_price"];
            $input["max_price"] = $userInput["max_price"];
            
            $locations = Location::pluck('name','id')->all();
    
            $parent_categories = Category::pluck('name','id')->all();

            if(array_key_exists($request->parent, $parent_categories)) {
                $input['category_id'] = $request->parent;
            } else {
                $input['category_id'] = 0;
            }
    
            if($request->status == 1) {
                $input['is_active'] = $request->status;
            } else {
                $input['is_active'] = 0;
            }

            $location_id = Auth::user()->location_id;
            $input['location_id'] = $location_id;

            if($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filename = $request->banner->getClientOriginalName();
                $path = $file->storeAs('public/style/banner/', $filename);
                $input['banner'] = $filename;
            }

            if($request->hasFile('category_img')) {
                $file_category_img = $request->file('category_img');
                $category_img = $request->category_img->getClientOriginalName();
                $path = $file_category_img->storeAs('public/style/topcategory/', $category_img);
                $input['category_img'] = $category_img;
            }

            // if($photo = $request->file('photo')) {
            //     $name = time().$photo->getClientOriginalName();
            //     $photo->move(Photo::getPhotoDirectoryName(), $name);
            //     $photo = Photo::create(['name'=>$name]);
            //     $input['photo_id'] = $photo->id;
            // }

            $category = Category::create($input);

            if(array_key_exists('specification_type', $input)) {
                $category->specificationTypes()->sync($input['specification_type']);
            }

            session()->flash('category_created', __("New category has been added."));

            return redirect(route('manage.categories.index'));
        } else {
            return view('errors.403');
        }
    }

    public function edit($id)
    {
        if(Auth::user()->can('update', Category::class)) {
            $location_id = Auth::user()->location_id;
            $category = Category::where('location_id', $location_id)->where('id', $id)->firstOrFail();
            $ignore_ids = $category->categories->pluck('id')->toArray();
            array_push($ignore_ids, $category->id);
            $ignore_ids = self::getAllChildCategoryIds($ignore_ids, $category->categories);
            $root_categories = Category::where('location_id', $location_id)->where('category_id', 0)->get();
            $specification_types = SpecificationType::where('location_id', Auth::user()->location_id)->get();
            return view('manage.categories.edit', compact('category', 'root_categories', 'ignore_ids', 'specification_types'));
        } else {
            return view('errors.403');
        }
    }

    private static function getAllChildCategoryIds(&$child_ids, $child_categories) {
        foreach($child_categories as $child_category) {
            if(count($child_category->categories) > 0) {
                $child_ids = array_merge($child_ids, $child_category->categories->pluck('id')->toArray());
                self::getAllChildCategoryIds($child_ids, $child_category->categories);
            }
        }
        return $child_ids;
    }

    public function update(CategoriesCreateRequest $request, $id)
    {
        
        $request->merge(['location' => Auth::user()->location_id]);
        if(Auth::user()->can('update', Category::class)) {
            $category = Category::findOrFail($id);
            $this->validate($request, [
                'name' => 'unique_with:categories,location=location_id,parent=category_id,'.$category->id
            ]);
            $userInput = $request->all();
            
            $input["name"] = $userInput["name"];
            $input["parent"] = $userInput["parent"];
            if(array_key_exists('specification_type', $userInput)) {
                $input["specification_type"] = $userInput["specification_type"];
            }
            
            $input["priority"] = isset($userInput["priority"]) ? $userInput["priority"] : 1;
            $input["top_category_priority"] = isset($userInput["top_category_priority"]) ? $userInput["top_category_priority"] : null; 
            $input["show_in_menu"] = $request->show_in_menu ? true : false;
            $input["show_in_footer"] = $request->show_in_footer ? true : false;
            $input["show_in_slider"] = $request->show_in_slider ? true : false;
            
            $input["show_filter_price"] = $request->show_filter_price ? true : false;
            $input["show_filter_metal"] = $request->show_filter_metal ? true : false;
            
            $input["show_filter_purity"] = $request->show_filter_purity ? true : false;
            $input["show_filter_gender"] = $request->show_filter_gender ? true : false;
            $input["show_filter_offers"] = $request->show_filter_offers ? true : false;
            $input["show_filter_short_by"] = $request->show_filter_short_by ? true : false;

            $input["meta_title"] = $userInput["meta_title"];
            $input["meta_desc"] = $userInput["meta_desc"];
            $input["meta_keywords"] = $userInput["meta_keywords"];
            $input["min_price"] = $userInput["min_price"];
            $input["max_price"] = $userInput["max_price"];
             
            $input['slug'] = '';
            $parent_categories = Category::pluck('name','id')->all();

            if(array_key_exists('specification_type', $input)) {
                $category->specificationTypes()->sync($input['specification_type']);
            } else {
                $category->specificationTypes()->sync([]);
            }

            if(array_key_exists($request->parent, $parent_categories)) {
                $input['category_id'] = $request->parent;
            } else {
                $input['category_id'] = 0;
            }
    
            if($request->status == 1) {
                $input['is_active'] = $request->status;
            } else {
                $input['is_active'] = 0;
            }
            if($request->hasFile('category_img')) {
                $file_category_img = $request->file('category_img');
                $category_img = $request->category_img->getClientOriginalName();
                $path = $file_category_img->storeAs('public/style/topcategory/', $category_img);
                $input['category_img'] = $category_img;
            }

            if($request->hasFile('banner')) {
                $file = $request->file('banner');
                $filename = $request->banner->getClientOriginalName();
                $path = $file->storeAs('public/style/banner/', $filename);
                $input['banner'] = $filename;
            }
            
            if($request->remove_category_photo) {
                
                if($request->old_category_img) {
                    
                    if(file_exists('storage/style/topcategory/'.$request->old_category_img)) {
                         
                        unlink('storage/style/topcategory/'.$request->old_category_img);
                        $input['category_img'] = '';
                    }
                }
            }
            
            if($request->remove_photo) {
                
                if($request->old_banner) {
                    
                    if(file_exists('storage/style/banner/'.$request->old_banner)) {
                       
                        unlink('storage/style/banner/'.$request->old_banner);
                        $input['banner'] = '';
                    }
                }
            }

            
            $category->update($input);

            

    
            session()->flash('category_updated', __("The category has been updated."));
            return redirect(route('manage.categories.edit', $category->id));
        } else {
            return view('errors.403');
        }
    }

    public function destroy($id)
    {
        if(Auth::user()->can('delete', Category::class)) {
            $category = Category::findOrFail($id);
            if($category->photo) {
                if(file_exists($category->photo->getPath())) {
                    unlink($category->photo->getPath());
                    $category->photo()->delete();
                }
            }
            $category->specificationTypes()->detach();
            $category->delete();
            session()->flash('category_deleted', __("The category has been deleted."));
            return redirect(route('manage.categories.index'));
        } else {
            return view('errors.403');
        }
    }

    public function deleteCategories(Request $request)
    {
        if(Auth::user()->can('delete', Category::class)) {
            if(isset($request->delete_single)) {
                $category = Category::findOrFail($request->delete_single);
                if($category->photo) {
                    if(file_exists($category->photo->getPath())) {
                        unlink($category->photo->getPath());
                        $category->photo()->delete();
                    }
                }
                $category->specificationTypes()->detach();
                $category->delete();
                session()->flash('category_deleted', __("The category has been deleted."));
            } else {
                if(isset($request->delete_all) && !empty($request->checkboxArray)) {
                    $categories = Category::findOrFail($request->checkboxArray);
                    foreach($categories as $category) {
                        if($category->photo) {
                            if(file_exists($category->photo->getPath())) {
                                unlink($category->photo->getPath());
                                $category->photo()->delete();
                            }
                        }
                        $category->specificationTypes()->detach();
                        $category->delete();
                    }
                    session()->flash('category_deleted', __("The selected categories have been deleted."));
                } else {
                    session()->flash('category_not_deleted', __("Please select categories to be deleted."));
                }
            }

            return redirect(route('manage.categories.index'));
        } else {
            return view('errors.403');
        }
    }

    public function getSpecifications(Request $request, $category_id) {
        if ($request->ajax()) {
            $category = Category::findOrFail($category_id);
            $category_specification_ids = $category->specificationTypes->pluck('id')->toArray();
            $specification_types = SpecificationType::whereNotIn('id', $category_specification_ids)->where('location_id', Auth::user()->location_id)->pluck('name', 'id')->toArray();
            return response()->json(['data' => $category->specificationTypes, 'more_specifications' => $specification_types]);
        }
    }
}
