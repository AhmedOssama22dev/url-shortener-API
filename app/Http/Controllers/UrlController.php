<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url;
use App\Helpers\Helper;
use App\Models\Ip;
use Exception;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use phpDocumentor\Reflection\Types\Null_;

class UrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Url::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'original'=>'required|url'
        ]);

        $original = $request->get('original');
        try{
        $unique_id = Url::latest('id')->first()->id + 1;
        }
        catch(Exception){
            $unique_id = 1;
        }

        $code = Helper::idEncode($unique_id);
        $host = $request->getSchemeAndHttpHost();
        $shortened = $host.'/'.$code;

        $user_id = Null;
        //This is not working
        if (Auth::check()) {
            $user_id = Auth::id();
        }
        return Url::create([
            'original' => $original,
            'shortened' => $shortened,
            'clicks' => 0,
            'user_id' => $user_id
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

        /**
     * Display the specified resource.
     *
     * @param  string  $code
     * @return \Illuminate\Http\Response
     */
    public function urlRedirect($code)
    {
        $id = Helper::idDecode($code);
        $url = Url::findOrFail($id);
        $url->clicks = $url->clicks + 1 ;
        $url->save();
        

        $ip_address = Helper::getIP();
        $region = Helper::getLocationInfoByIp($ip_address);

        $ip_exist = Ip::where('address', $ip_address)->get();
        //return $ip_exist;
        if(count($ip_exist)<1)
        {
            $ip = new Ip;
            $ip->address = $ip_address;
            $ip->country = $region['country'];
            $ip->city = $region['city'];
            $ip->save();
            $ip->urls()->attach($url);
        }
        try{
            $original = $url->original;
            return Redirect::to($original);
        }
        catch(Exception)
        {
            return abort(404);
        }
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return Url::destroy($id);
    }
}
