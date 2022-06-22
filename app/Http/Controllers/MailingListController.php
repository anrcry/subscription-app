<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Website;
use App\Models\Subscriber;
use App\Models\Post;

class MailingListController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $id = 1;
        // $x = Post::find($id)->website->subscribers()->get();
        // foreach($x as $y){
        //     var_dump($y->email);
        // }
        // dd($x);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $response = ['success'=>false];

        if(is_null($id) || !is_numeric($id)){
            $response['message'] = "The `website_id` in path is mandatory and must be numeric";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }
        $website = Website::find((int)($id));
        
        if($website === NULL){
            $response['message'] = "Website with the id {$id} does not exist";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }
        
        $email = $request->input('email', false);
        $name = $request->input('name', null);

        if($email === false || !is_string($email) || $email !== filter_var($email, FILTER_SANITIZE_EMAIL) || filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) === false){
            $response['message'] = "Your `email` as a query is mandatory and must be of type example@example.com";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        $subscribers = $website->subscribers();

        if($subscribers->count() == 0){
            $response['message'] = "Your email ${email} is not a subscriber of website id = {$website->id}";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $subscriber = $subscribers->where('email', $email)->limit(1)->first() ?: new Subscriber;

        if($subscriber->email === $email){
            $response['message'] = "Your email ${email} is already a subscriber of website id {$website->id} from {$subscriber->subscribed_at}.";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        if($name !== NULL){
            $subscriber->name = $name;
        }

        $subscriber->email = $email;
        $subscriber->website_id = (int)($id);

        $subscriber->save();

        $response['message'] = 'Subscriber added successfully';
        $response['success'] = true;
        
        $response['info'] = [
            'email'=>$subscriber->email,
            'name'=>$subscriber->name,
            'subscribed_at'=>$subscriber->subscribed_at
        ];

        return response()->json($response, 201, [], parent::JSON_RESPONSE);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {

        $response = ['success'=>false];

        if(is_null($id) || !is_numeric($id)){
            $response['message'] = "The `website_id` in path is mandatory and must be numeric";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }
        $website = Website::find((int)($id));
        
        if($website === NULL){
            $response['message'] = "Website with the id {$id} does not exist";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }
        
        $email = $request->input('email', false);

        if($email === false || !is_string($email) || $email !== filter_var($email, FILTER_SANITIZE_EMAIL) || filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) === false){
            $response['message'] = "Your `email` as a query is mandatory and must be of type example@example.com";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        $subscribers = $website->subscribers();

        if($subscribers->count() == 0){
            $response['message'] = "Your email ${email} is not a subscriber of website id = {$website->id}";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $subscriber = $subscribers->where('email', $email)->limit(1)->first();

        if($subscriber === NULL){
            $response['message'] = "Your email ${email} is not a subscriber of website id {$website->id}";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $response['info'] = [
            'email'=>$subscriber->email,
            'name'=>$subscriber->name,
            'subscribed_at'=>$subscriber->subscribed_at
        ];

        $response['success'] = true;

        return response()->json($response, 200, [], parent::JSON_RESPONSE);
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

        $response = ['success'=>false];

        if(is_null($id) || !is_numeric($id)){
            $response['message'] = "The `website_id` in path is mandatory and must be numeric";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }
        $website = Website::find((int)($id));

        if($website === NULL){
            $response['message'] = "Website with the id {$id} does not exist";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }

        $email = $request->input('email', false);
        $name = $request->input('name', null);

        if($email === false || !is_string($email) || $email !== filter_var($email, FILTER_SANITIZE_EMAIL) || filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) === false){
            $response['message'] = "Your `email` as a query is mandatory and must be of type example@example.com";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        $subscribers = $website->subscribers();

        if($subscribers->count() == 0){
            $response['message'] = "Your email ${email} is not a subscriber of website id = {$website->id}";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }
        
        $subscriber = $subscribers->where('email', $email)->limit(1)->first();

        if($subscriber === NULL){
            $response['message'] = "Your email ${email} is not a subscriber of website id {$website->id} ";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        if($subscriber->name !== $name && $name !== NULL){
            // Something gets updated..
            $subscriber->name = $name;
            $subscriber->save();
            $response['message'] = 'Subscriber information updated successfully';
        }

        $response['success'] = true;

        $response['info'] = [
            'email'=>$subscriber->email,
            'name'=>$subscriber->name,
            'subscribed_at'=>$subscriber->subscribed_at
        ];


        return response()->json($response, 200, [], parent::JSON_RESPONSE);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {

        $response = ['success'=>false];

        if(is_null($id) || !is_numeric($id)){
            $response['message'] = "The `website_id` in path is mandatory and must be numeric";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }
        $website = Website::find((int)($id));

        if($website === NULL){
            $response['message'] = "Website with the id {$id} does not exist";
            return response()->json($response, 400, [], parent::JSON_RESPONSE);
        }

        $email = $request->input('email', false);

        if($email === false || !is_string($email) || $email !== filter_var($email, FILTER_SANITIZE_EMAIL) || filter_var(filter_var($email, FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL) === false){
            $response['message'] = "Your `email` as a query is mandatory and must be of type example@example.com";
            return response()->json($response, 406, [], parent::JSON_RESPONSE);
        }

        $subscribers = $website->subscribers();

        if($subscribers->count() == 0){
            $response['message'] = "Your email ${email} is not a subscriber of website id = {$website->id}";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $subscriber = $subscribers->where('email', $email)->limit(1)->first();

        if($subscriber === NULL){
            $response['message'] = "Your email ${email} not a subscriber of website id {$website->id} ";
            return response()->json($response, 404, [], parent::JSON_RESPONSE);
        }

        $subscriber->delete();

        $response['success'] = true;

        return response()->json($response, 200, [], parent::JSON_RESPONSE);
    }
}
