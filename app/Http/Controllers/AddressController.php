<?php

namespace App\Http\Controllers;

use App\Mail\NewAddressAdded;
use App\Models\Address;
use App\Models\User;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $limit = $request->input('limit', -1);

        if ($user->hasRole('admin')) 
            return Address::limit($limit)->get();

        return Address::where('user_id', $user->id)->limit($limit)->get();
    }

    public function search(Request $request) 
    {
        $user = auth()->user();
        $limit = $request->input('limit', -1);
        $words = $request->input('search');
        if (empty($words))
            $words = [];
        else
            $words = explode(' ', $words);

        $segments = [];
        foreach($words as $word){
            $items = [];
            $items[] = "state LIKE '%$word%'";
            $items[] = "county LIKE '%$word%'";
            $items[] = "city LIKE '%$word%'";
            $items[] = "zip LIKE '%$word%'";
            $items[] = "street LIKE '%$word%'";
            $items[] = "house_number LIKE '%$word%'";
            $segments[] = '('.implode(" OR ", $items).')';
        }

        if (!$user->hasRole('admin')) 
            $segments[] = "user_id = $user->id";

        $where = '';
        if(count($segments) > 0) 
            $where .= 'WHERE ' . implode(" AND ", $segments);

        $sql = 'SELECT * FROM addresses ' . $where . (($limit > -1) ? ' LIMIT ' . $limit : '');

        return DB::select(DB::raw($sql));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'user_id' => 'required',
            'state' => 'required',
            'county' => 'required',
            'city' => 'required',
            'zip' => 'required',
            'street' => 'required',
            'house_number' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        $address = Address::create([
            'user_id' => $fields['user_id'],
            'state' => $fields['state'],
            'county' => $fields['county'],
            'city' => $fields['city'],
            'zip' => $fields['zip'],
            'street' => $fields['street'],
            'house_number' => $fields['house_number'],
            'latitude' => $fields['latitude'],
            'longitude' => $fields['longitude'],
        ]);


        $users = User::whereRoleIs('admin')->get();
        Mail::to($users)->send(new NewAddressAdded($address, auth()->user()));
        
        return $address;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $address = Address::find($id);

        if(!$address) {
            return response()->json([
                'message' => 'Address not found',
            ], 404);
        }

        if($user->cannot('update', $address)) {
            return response()->json([
                'message' => 'Access denied',
            ], 403);
        }        

        $address->user_id = $request->input('user_id', $address->user_id);
        $address->state = $request->input('state', $address->state);
        $address->county = $request->input('county', $address->county);
        $address->city = $request->input('city', $address->city);
        $address->zip = $request->input('zip', $address->zip);
        $address->street = $request->input('street', $address->street);
        $address->house_number = $request->input('house_number', $address->house_number);
        $address->latitude = $request->input('latitude', $address->latitude);
        $address->longitude = $request->input('longitude', $address->longitude);

        $address->save();
        return $address;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        $user = auth()->user();
        $address = Address::find($id);

        if($user->cannot('update', $address)) {
            return response()->json([
                'message' => 'Access denied',
            ]);
        }

        if(!$address) {
            return response()->json([
                'message' => 'Address not found',
            ]);
        }

        $address->delete();
        return response()->json([
            'message' => 'Address deleted',
        ]);
    }
}
