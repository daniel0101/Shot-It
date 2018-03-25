<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JD\Cloudder\Facades\Cloudder;
use App\Item;
use Validator;

class ItemController extends Controller
{
    public $tags;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(Item::all(),200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateData($request->all());
        //upload image
        $response = $this->uploadImage($request->item->getPathName(),null,null,$request->tags);
        $result = $response->getResult();
        if(isset($result['public_id'])){   
            $request->public_id = $result['public_id'];  
            $request->item_url = $result['url'];
            $request->item_type = $this->getItemType($request->item);
            $data = ['user_id'=>$request->user_id,
                    'category_id'=>$request->category_id,
                    'public_id'=>$request->public_id,
                    'item_url'=>$request->item_url,
                    'item_type'=>$request->item_type,
                    'title'=>$request->title,
                    'description'=>$request->description,
                    'tags'=>$request->tags
                ];
            if(Item::create($data)){
                return response()->json(['status'=>'ok','message'=>'data saved'],200);                
            }else{
                return response()->json(['status'=>'error','message'=>'data could not be saved'],400);
            }    
        }else{                        
            return response()->json(['status'=>'error','message'=>'data could not be saved'],400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $image = Item::findOrFail($id); 
        return response()->json($image,200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $image = Item::findOrFail($id); 
        return response()->json($image,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //validate data
        $validate = $this->validateData($request->all());
        $image = Item::findOrFail($id);
        $updated = $image->update($request->all());
        if($updated){
            return response()->json(['status'=>'ok','message'=>'Update was successful'],200);
        }else{                        
            return response()->json(['status'=>'error','message'=>'Update was not successful'],400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $image = Image::findOrFail($id);
       if($image->delete()){
           return response()->json(['status'=>'ok','message'=>'data deleted successfully'],200);
       }else{
            return response()->json(['status'=>'error','message'=>'data could not be deleted'],400);
        }
    }

    /**
     * Search the item model with elastic search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function search(Request $request)
    {
        $result = '';
        if($request->has('search')){
            $query_string = $request->input('search');
            $result = Item::search($query_string);
        }else{
            $result = Item::search('*');
        }
        return response()->json(['status'=>'ok','message'=>$result],200);
    }

    public function validateData($data){
        $rules = [
            'item_url'=>'String',
            'title'=>'String|max:100',
            'description'=>'String',
            'tags'=>'String',
            'file' => 'required|file|mimes:jpeg,png,jpg,gif,svg,mp4|max:2048'
        ];

        return Validator::make($data,$rules);
    }

    public function uploadImage($filename,$tags){
        $this->tags = explode(',',$tags);
        // Cloudder::upload($filename, $publicId, array $options, array $tags);
        $response = Cloudder::upload($filename,null,[],$tags);
        return $response;        
    }  
    
    public function getItemType($file){
        $extension = $file->getClientOriginalExtension();
        switch ($extension) {
            case 'png':
                return 'Image';
                break;
            case 'jpg':
                return 'Image';
                break;
            case 'jpeg':
                return 'Image';
                break;
            case 'jpeg':
                return 'Image';
                break;        
            case 'gif':
                return 'Image-gif';
                break;
            case 'svg':
                return 'Image-svg';
                break;
            case 'mp4':
                return 'Video';
                break;                     
            default:
                return 'Invalid Item';
                break;
        }
    }

}
