<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Nothing
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        list($name, $description) = array($request->input('name'), $request->input('description') ?? NULL);

        if(null === $name || !is_string($name) || strlen($name) == 0){
            // Some error
        }

        $website = new Website;
        $website->name = $name;
        $website->description = $description;

        $website->save();

        return response()->json(array('success' => true, 'message'=> 'Successfully added a new website', "website"=>['id' => $website->id, 'name'=>$website->name, 'description'=>$website->description, 'created_at'=>$website->created_at]), 201, [], parent::JSON_RESPONSE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id=NULL)
    {
        $website = null;
        $response = ['success'=>true];

        if(null !== $id && !is_numeric($id)){
            // ID has to be null or numeric..
            $response['success'] = false;
            $response['message'] = 'The `website_id` path if given has to be numeric!';
            // Utilizing unused variable;
            $website = 400;
        }else if(is_numeric($id)){
            $website = Website::find((int)($id));
            if($website === NULL){
                $response['success'] = false;
                $response['message'] = "The `website_id` could not be found!";
                $website = 404;
            }else{
                $response['website'] = array(
                    'id'=>$website->id,
                    'name'=>$website->name,
                    'description'=>$website->description,
                    'created_at'=>$website->created_at,
                );
            }
            
        }else{
            $response['websites'] = [];
            foreach(Website::all() as $website){
                $response['websites'][] = array(
                    'id'=>$website->id,
                    'name'=>$website->name,
                    'description'=>$website->description,
                    'created_at'=>$website->created_at,
                );
            }
            $response['count'] = count($response['websites']);
        }

        return response()->json($response, $response['success'] ? 200 : $website, [], parent::JSON_RESPONSE);
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
        // Update is conditional
        // If a PUT request is encountered only some or all parameters are expected from the body..
        // In case of POST request all parameters are expected from the body.
        $response = ['success'=>false];
        if(null === $id || !is_numeric($id)){
            $response['message'] = 'The `website_id` path has to be numeric!';
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }
        
        $website = Website::find((int)($id));
        if(null === $website){
            $response['message'] = 'Website with '.$id.'could not be found';
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }
        
        $name = $request->input('name', false);
        $description = $request->input('description', false);
        
        if((!$name && !$description) || ($request->isMethod('post') && (!$name || !$description))){
            $response['message'] = "Property (name or description or both) must be given.";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        if($name !== false){
            $website->name = $name;
        }

        if($description !== false){
            $website->description = $description;
        }

        $website->save();

        $response['success'] = true;
        $response['message'] = "Website details has been updated!";

        $response['website'] = array(
            'id'=>$website->id,
            'name'=>$website->name,
            'description'=>$website->description,
            'created_at'=>$website->created_at,
        );

        return response()->json($response, 200, [], parent::JSON_RESPONSE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return response()->json(['success'=>false, 'message'=>'Invalid request'], 400, [], parent::JSON_RESPONSE);
    }
}
