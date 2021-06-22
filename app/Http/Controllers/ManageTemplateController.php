<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Template;

class ManageTemplateController extends Controller
{
    public function index() {
        $template = Template::all();
        $templateArray = array();
        $registerTemplate = array();
        $forgetTemplate = array();
        $orderCancelTemplate = array();
        $orderPlaceTemplate = array();

         
        if(!empty($template)) {
            foreach($template AS $value) {
                if($value->template_name == 'Registraion') {
                    $registerTemplate = ['template_name' => $value->template_name, 'template_subject' => $value->template_subject,'template_body' => $value->template_body];
                } elseif($value->template_name == 'Forget Password') {
                    $forgetTemplate = ['template_name' => $value->template_name, 'template_subject' => $value->template_subject,'template_body' => $value->template_body];
                } elseif($value->template_name == 'Order Placement') {
                    $orderPlaceTemplate = ['template_name' => $value->template_name, 'template_subject' => $value->template_subject,'template_body' => $value->template_body];
                } elseif($value->template_name == 'Order Cancellation') {
                    $orderCancelTemplate = ['template_name' => $value->template_name, 'template_subject' => $value->template_subject,'template_body' => $value->template_body];
                }
                 
            }
        }
        
         
        return view('manage.template.index',compact('registerTemplate','forgetTemplate','orderPlaceTemplate','orderCancelTemplate'));
    }
    public function store(Request $request) {
        
        $template = new Template;
        
        if(count($request->template_subject) > 0) {
            
            $templateArray = array();
            
            foreach($request->template_subject AS $k => $value) {
                
                if($value!=null) {
                    $containers[] =  [
                            'template_name' => $request->template_name[$k],
                            'template_subject' => $value,
                            'template_body' => $request->template_body[$k]
                             
                        ];
                   
                    
                }
            }
             
            Template::truncate();
            \DB::table('templates')->insert($containers);
        }
        
        return redirect()->back();
        
    }
     
}
