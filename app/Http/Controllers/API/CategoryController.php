<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

public function index()
{
    $category= Category::all();
    return response()->json([
        'status'=>200,
        'category'=>$category,
    ]);
}


public function allcategory(){

    $category= Category::where('status', 'o')->get();
    return response()->json([
        'status'=>200,
        'category'=>$category,
    ]);

}


public function edit( $id){

    $category = Category::find($id);
    
    if($category)
    {
        return response()->json([
            
            'category'=>$category
        ],200);
    }else{
        return response()->json([
           
            'message'=>'No Category Id Found'
        ],404);
    }

}




    public function store (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'meta_title'=>'required|max:191',
            // 'meta_keyword'=>'required|max:191',
            // 'meta_descrip'=>'required',
            'slug'=>'required|max:191',
            'name'=>'required|max:191',
            // 'description'=>'required',
            // 'status'=>'required',



        ]);
        if($validator->fails()) 
        {
            return response()->json([
                'status'=>400,
                'error'=>$validator->messages(),
            ]);
        }else{

            $category= new Category;
$category->meta_title= $request->input('meta_title');
$category->meta_keyword= $request->input('meta_keyword');
$category->meta_descrip= $request->input('meta_descrip');
$category->slug= $request->input('slug');
$category->name= $request->input('name');
$category->description= $request->input('description');
$category->status= $request->input('status') == true ? '1':'0';

$category->save();
return response()->json([
    'status'=>200,
    'messages'=>'Category Added Successfully',
]);

    }
            
        }

        public function update (Request $request ,$id){
            $validator = Validator::make($request->all(),[
                'meta_title'=>'required|max:191',
                // 'meta_keyword'=>'required|max:191',
                // 'meta_descrip'=>'required',
                'slug'=>'required|max:191',
                'name'=>'required|max:191',
                // 'description'=>'required',
                // 'status'=>'required',
    
    
    
            ]);
            if($validator->fails()) 
            {
                return response()->json([
                    'status'=>422,
                    'error'=>$validator->messages(),
                ]);
            }else{
    
                $category=  Category::find($id);
                if($category){ 
                    
                    $category->meta_title= $request->input('meta_title');
                     $category->meta_keyword= $request->input('meta_keyword');
                     $category->meta_descrip= $request->input('meta_descrip');
                  $category->slug= $request->input('slug');
                 $category->name= $request->input('name');
                   $category->description= $request->input('description');
                  $category->status= $request->input('status') == true ? '1':'0';
    
    $category->save();
    return response()->json([
        'status'=>200,
        'messages'=>'Category updated Successfully',
    ]);

                }else{
                    return response()->json([
                        'status'=>404,
                        'messages'=>'No Category Id Found',
                    ]);
                }
   
    
        }
            

        }




        public function destroy($id){

            $category= Category::find($id);
            if ( $category) {
                $category->delete();
                return response()->json([
                    'status'=>200,
                    'messages'=>'Category Deleted Successfully',
                ]);

               
            } else {

                return response()->json([
                    'status'=>404,
                    'messages'=>'No Category Id Found',
                ]);
              
            }
            

        }


};
