<?php

namespace App\Http\Controllers;

use App\Http\Requests\UsersCreateRequest;
use App\Http\Requests\UsersUpdateRequest;
use App\Location;
use App\Photo;
use App\Role;
use App\User;
use App\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Events\Auth\UserActivationEmail;
use DB;
use App\Address;
use App\Customer;
use App\Mobile;
use App\Order;
use App\Product;

use App\Voucher;

class ManageUsersController extends Controller
{
    public function index()
    {
        if(Auth::user()->can('read', User::class)) {
            $users = User::where('role_id','3')->get();
            $default_photo = Photo::getDefaultUserPhoto();
            return view('manage.users.index', compact('users', 'default_photo'));
        } else {
            return view('errors.403');
        }
    }

    public function create()
    {
        if(Auth::user()->can('create', User::class)) {
            $roles = Role::whereNotIn('id', [1,2])->pluck('name','id')->toArray();
            
            return view('manage.users.create', compact('roles'));
        } else {
            return view('errors.403');
        }
    }

    public function store(UsersCreateRequest $request)
    {
        
        if(Auth::user()->can('create', User::class)) {
            $userInput = $request->all();
            $input["name"] = $userInput["name"];
            
            if($request->phone_number) {

                $userCount = User::where('phone',$request->phone_number)->count();
                if($userCount > 0 ) {
                    session()->flash('user_created_error', __("Mobile already exist."));
                    return redirect()->back()->withInput($request->input());
                } else {
                    $input["phone"] = isset($userInput["phone_number"]) ? $userInput["phone_number"] : null;
                }

                  
            }
            $input["email"] = isset($userInput["email"]) ? $userInput["email"] : null;

            $roles = Role::pluck('name','id')->except(1)->toArray();

            if(array_key_exists($request->role, $roles)) {
                $input['role_id'] = $request->role;
            } else {
                $input['role_id'] = 0;
            }

            $location_id = Auth::user()->location_id;
            $input['location_id'] = $location_id;

            if($request->status == 1) {
                $input['is_active'] = $request->status;
            } else {
                $input['is_active'] = 0;
            }

            $input['activation_token'] = str_random(191);
            $input['verified'] = isset($request->verified) ? $request->verified : false;

            $input['password'] = bcrypt($request->password);
            $user = User::create($input);

            if($request->phone_number) {
                $phone_no = $request->phone_number;
                $verified = $request->verified;
                $user_id = $user->id;
                $mobile = new Mobile(['number' => $phone_no, 'verified'=>$verified,'user_id'=>$user_id]);
                $user->mobile()->save($mobile);
            }
            if($request->email!=null) {
                event(new UserActivationEmail($user));
            }
            

            session()->flash('user_created', __("New user has been added."));
    
            return redirect(route('manage.users.index'));
        } else {
            return view('errors.403');
        }
    }

    public function edit($id)
    {
        if(Auth::user()->can('update', User::class)) {
            // if($id == 1 && Auth::user()->id != 1) {
            //     return view('errors.404');
            // }
            $user = User::findOrFail($id);
            $roles = Role::whereNotIn('id', [1])->pluck('name','id')->toArray();
            $locations = Location::pluck('name', 'id')->toArray();
            return view('manage.users.edit', compact('user', 'roles', 'locations'));
        } else {
            return view('errors.403');
        }
    }

    public function getUserData($user_name)
    {
        if(Auth::user()->can('read', User::class)) {
            if(Auth::user()->id != 1) {
                return 0;
            }
            $user = User::where('username',$user_name)->first();
            if ($user){
                $already_vendor = Vendor::where('user_id',$user->id)->first();
                if ($already_vendor){
                    return 0;
                }
            }
            return json_encode($user);
        } else {
            return 0;
        }
    }

    public function update(UsersUpdateRequest $request, $id)
    {
       
        
        $user = User::where('id',$id)->first();
        
        // $this->validate($request, [
        //     'email' => 'unique:users,email,'.$user->id
            
        // ]);

        if(trim($request->password) == "") {
            $userInput = $request->except('password');
        } else {
            $userInput = $request->all();
            $input['password'] = bcrypt($request->password);
        }

        $input["name"] = $userInput["name"];
        
        if(isset($request->phone_number) && $request->phone_number!=null) {
           
            $userCount = User::where('phone',$request->phone_number)->where('id','<>',\Request::segment(3))->count();
            if($userCount > 0 ) {
                session()->flash('user_updated_error', __("Mobile already exist."));
                return redirect()->back()->withInput($request->input());
            } else {
                $input["phone"] = isset($userInput["phone_number"]) ? $userInput["phone_number"] : null;
            }
             
                
        }
        $input["email"] = isset($userInput["email"]) ? $userInput["email"] : null;

        if($id != 1) {
            $input['is_active'] = $request->status;

            $roles = Role::pluck('name','id')->except(1)->toArray();

            if(array_key_exists($request->role, $roles)) {
                $input['role_id'] = $request->role;
            } else {
                $input['role_id'] = 0;
            }

            $input['verified'] = $request->verified;
            if($input['verified'] == 1) {
                $input['activation_token'] = NULL;
            }
        }

        if(Auth::user()->can('update', Location::class)) {
            $locations = Location::pluck('name','id')->all();                    
            if(array_key_exists($request->location, $locations)) {
                $input['location_id'] = $request->location;
            } else {
                $input['location_id'] = 1;
            }
        }

        

        $old_email = $user->email;

        if($old_email != $request->email) {
            if($user->id != 1){
                $input['verified'] = false;
            }
            $input['activation_token'] = str_random(191);
        }

        if($request->has('remove_staff') && !$user->role) {
            $input['location_id'] = null;
            $input['role_id'] = 0;
        }

        $old_location_id = $user->location_id;

        $user->update($input);

        if($request->email != $old_email) {
            event(new UserActivationEmail($user));
        }
        if(isset($request->phone_number) && $request->phone_number!=null) {
            $phone_no = $request->phone_number;
            $verified = $request->verified;
            $user_id = $user->id;
            if(isset($user->mobile)) {
                $user->mobile->delete();
            }
            
            $mobile = new Mobile();
            $mobile->user_id = $user_id;
            $mobile->verified = $verified;
            $mobile->number = $phone_no; 
            $mobile->save();
            
        } 
        session()->flash('user_updated', __("The user has been updated."));

        if($old_location_id != $user->location_id) {
            return redirect(route('manage.users.index'));
        }

        return redirect(route('manage.users.edit', $user->id));
    }

    public function deleteUser($id)
    {
        
      
       DB::beginTransaction();
        try {
            $user = User::findOrfail($id);
            
           
                if(!empty($user->addresses)) {
                    foreach ($user->addresses as $address) {
                        $address->delete();
                    }
                    
                }
                
                if(!empty($user->customers)) {
                    foreach ($user->customers as $customers) {
                        $customers->delete();
                    }
                    
                }
               
                if(!empty($user->mobile)) {
                    $user->mobile->delete();
                }
                
                if(!empty($user->orders)) {
                    foreach ($user->orders as $orders) {
                        $orders->delete();
                    }
                    
                }
                
                if(!empty($user->products)) {
                    foreach ($user->products as $products) {
                        $products->delete();
                    }
                }
               
                // if(!empty($user->wallets)) {
                //     foreach ($user->wallets as $wallets) {
                //         $wallets->delete();
                //     }
                // }
               
                if($user->vouchers) {
                    foreach ($user->vouchers as $vouchers) {
                        $vouchers->delete();
                    }
                }
                
                $user->delete();
            
            
            
           
            
            DB::commit();
        } catch(\Exception $e) {
            DB::rollback();
            dd($e->getMessage());
            return back()->withError($e->getMessage())->withInput();
        }
        return redirect()->route('manage.users.index')->with('message', 'User has been delete successfully');
    }

    
}
