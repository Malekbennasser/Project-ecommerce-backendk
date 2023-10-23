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




    public function store (Request $request)
    {
        $validator = Validator::make($request->all(),[
            'meta_title'=>'required|string|max:100',
            'slug'=>'required|string|max:100',
            'name'=>'required|string|max:100',
         
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
           public function edit( $id){


               $category = Category::find($id);
    
                   if($category)
                      {
                   return response()->json([
                         'status'=>200,
                         'category'=>$category
                             ]);
               }else
               {
               return response()->json([
                    'status'=>404,
                      'message'=>'No Category Id Found'
              ]);
              }

         }

        public function update (Request $request ,$id){
            $validator = Validator::make($request->all(),[
                'meta_title'=>'required|string|max:100',
                'slug'=>'required|string|max:100',
                'name'=>'required|string|max:100',
            ]);
            if($validator->fails()) 
            {
                return response()->json([
                    'status'=>422,
                    'error'=>$validator->messages(),
                ]);
                      }else{
    
                $category=Category::find($id);
                if($category){ 
                    
                $category->meta_title= $request->input('meta_title');
                $category->meta_keyword= $request->input('meta_keyword');
                $category->meta_descrip= $request->input('meta_descrip');
                $category->slug= $request->input('slug');
                 $category->name= $request->input('name');
                $category->description= $request->input('description');
                $category->status= $request->input('status') == true ? '1':'0';
    
                    $category->update();
                         return response()->json([
                             'status'=>200,
                              'messages'=>'Category updated Successfully',
                            ]);

                }else{
                    return response()->json([
                        'status'=>404,
                        'error'=>'No Category Id Found',
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
