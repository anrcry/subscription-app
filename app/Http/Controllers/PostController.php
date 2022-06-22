<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Website;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response 
     */
    public function store($id, Request $request)
    {
        $response = ['success'=>false];
        
        if($id !== NULL && !is_numeric($id)){
            $response['message'] = 'The `website_id` path has to be numeric!';
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }

        $website = Website::find((int)($id));

        if($website === NULL){
            $response['message'] = "The website with ${id} could not be found!";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $contents = $request->input('contents', NULL);
        $title = $request->input('title', false);

        if(!$title){
            $response['message'] = "Cannot store a post without a title";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        $post = new Post([
            'title'=>$title,
            'contents'=>$contents,
        ]);

        $website->posts()->save($post);
        
        $response['success'] = true;
        $response['post'] = [
            'id'=>$post->id,
            'title'=>$post->title,
            'contents'=>$post->contents,
        ];

        return response()->json($response, 201, [], parent::JSON_RESPONSE);
    }

    /**
     * Display the specified resource.
     * @param  int|null  $website_id
     * @param  int|null  $post_id
     * @return \Illuminate\Http\Response
     */
    public function show($website_id = null, $post_id = null, Request $request){
        
        $response = ['success'=>false];

        if($request->routeIs('post.show')){
            // SWAP
            list($post_id, $website_id) = array($website_id, $post_id);
        }

        if($website_id !== NULL && !is_numeric($website_id)){
            $response['message'] = 'The `website_id` path has to be numeric!';
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }

        if($post_id !== NULL && !is_numeric($post_id)){
            $response['message'] = 'The `post_id` path (if given) has to be numeric!';
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }

        $website = NULL; 
        $post = NULL;
        if($website_id !== null){
            $website = Website::find((int)($website_id));
            if($website === NULL){
                $response['message'] = "The website with ${website_id} could not be found!";
                return response()->json($response, 404, [], parent::JSON_RESPONSE);
            }
        }

        if($post_id !== NULL){
            $post = Post::find((int)($post_id));
            if($post === NULL){
                $response['message'] = "The post with ${post_id} could not be found!";
                return response()->json($response, 404, [], parent::JSON_RESPONSE);
            }
        }

        $response['success'] = true;

        if($post === NULL){
            // So all posts..
            $post = Post::all();
            $response['posts'] = [];
            foreach($post as $p){
                $x = array(
                    'id'=>$p->id,
                    'title'=>$p->title,
                    'contents'=>$p->contents,
                    'created_at'=>$p->created_at,
                    'updated_at'=>$p->updated_at,
                    'website'=>[
                        'id'=>$p->website->id,
                        'name'=>$p->website->name,
                        'description'=>$p->website->description,
                        'created_at'=>$p->website->created_at,
                    ]
                );

                if($website !== NULL){
                    if($p->website->id != $website->id){
                        continue;
                    }else{
                        unset($x['website']);
                    }
                }
                $response['posts'][] = $x;
            }
            
            $response['count'] = count($response['posts']);
            return response()->json($response, 200, [], parent::JSON_RESPONSE);
        }

        if($post !== NULL){
            // Send this specific post (if website given or not given).
            $response['post'] = array(
                'id'=>$post->id,
                'title'=>$post->title,
                'contents'=>$post->contents,
                'created_at'=>$post->created_at,
                'updated_at'=>$post->updated_at,
                'website'=>[
                    'id'=>$post->website->id,
                    'name'=>$post->website->name,
                    'description'=>$post->website->description,
                    'created_at'=>$post->website->created_at
                ],
            );
            
            if($website !== NULL){
                if($post->website->id != $website->id){
                    $response['success'] = false;
                    $response['message'] = "The post with id {$post_id} does not belong to the website with id {$website_id}";
                    return response()->json($response, 404, [], parent::JSON_RESPONSE);
                }else{
                    unset($response['post']['website']);
                }
            }
            return response()->json($response, 200, [], parent::JSON_RESPONSE);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $website_id = null, $post_id = null)
    {

        if($request->routeIs('post.update')){
            // SWAP
            list($post_id, $website_id) = array($website_id, $post_id);
        }

        // var_dump($website_id, $post_id);
        
        $response = ['success'=>false];
        
        if($post_id === NULL || !is_numeric($post_id)){
            $response['message'] = 'The `post_id` path is required and must be numeric!';
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }

        if($website_id !== NULL && !is_numeric($website_id)){
            $response['message'] = 'The `website_id` path (if given) must be numeric!';
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }

        $contents = $request->input('contents', false);
        $title = $request->input('title', false);

        if((!$title && !$contents) || ($request->isMethod('post') && (!$title || !$contents))){
            $response['message'] = "Property (title or contents or both) must be given.";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        $website = ($website_id === NULL) ? false : Website::find((int)($website_id));

        if($website !== false){
            $response['message'] = "The website with ${website_id} could not be found!";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $post = Post::find((int)($post_id));

        if($post == NULL){
            $response['message'] = "The post with ${post_id} could not be found!";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        if($website !== false && $website->id !== $post->website_id){
            // Does not belong
            $response['message'] = "The post with id {$post_id} does not belong to the website with id {$website_id}";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $post->title = $title !== false ? $title : $post->title;
        $post->contents = $contents !== false ? $contents : $post->contents;

        $post->save();

        $response['success'] = true;
        $response['message'] = "Post details has been updated!";

        $response['post'] = array(
            'id'=>$post->id,
            'name'=>$post->title,
            'description'=>$post->contents,
            'created_at'=>$post->created_at,
            'updated_at'=>$post->updated_at,
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
        //
    }
}
