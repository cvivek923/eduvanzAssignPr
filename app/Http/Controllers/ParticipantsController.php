<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Participants;

class ParticipantsController extends Controller
{
    public function index(Request $request)
    {
        /*
            This function is called to get all the participants.

            @paginate param is used to check for pagination and set the pagination limit

            @searchName param is used to search for participant with their name

            @searchLocality param is used to search for participants based on their locality

        */
        $param = $request->all();
        $query = auth()->user()->participants();

        //search based on the name. Do not remove white space in between with other special characters.
        if(isset($param['searchName']) && !empty(trim($param['searchName'])) ){
            $searchName = preg_replace('/[^A-Za-z \-]/', '', $param['searchName']);
            $query->where('name',$searchName);
        }

        //search based on locality
        if(isset($param['searchLocality']) && !empty(trim($param['searchLocality'])) ){
            $searchLocality = preg_replace('/[^A-Za-z0-9 \-]/', '', $param['searchLocality']);
            $query->where('locality',$searchLocality);
        }

        //
        if(isset($param['paginate']) && is_numeric($param['paginate']) ){
            $participants = $query->paginate($param['paginate']);
        }else{
            $participants = $query->get();
        } 
        return response()->json([
            'success' => true,
            'data' => $participants
        ]);
    }
 
    public function show($id)
    {
        ////nothing to do here
    }

    public function store(Request $request)
    {
        /*
            This function is used to store/Create new participant 
            @name - String Value upto 255 char limit
            @age - int value between 18 to 55
            @guests - Number of guests coming along with participant. Accepts upto 2 Guests.
            @date_of_birth - Date of birth of the participant in Y-m-d format
            @profession - Profession of participant - Employed or Student
            @locality - accepts string value upto 255 chars
            @address - Accepts longtext upto 50 chars
        */
        $this->validate($request, [

            //required and 255 max length allowed
            'name' => 'required|max:255',

            //Only visitors between the age of 18 to 55 are allowed
            'age' => 'required|integer|between:18,55',

            //Limiting the number of guests upto 2
            'guests' => 'required|integer|between:0,2',

            //Validating the date format Y-m-d
            'date_of_birth' => 'required|date_format:Y-m-d',

            //Only accepts either Employed or Student value
            'profession' => 'required|in:Employed,Student',

            ////required and 255 max length allowed
            'locality' => 'required|max:255',

            //Limiting the character length to 50 chars
            'address' => 'required|min:5|max:50'
        ]);
 
        /*
            If we have a lot of field in some other case, we should build a function in model which will return all the fillables filed.
            Later we can loop through those fields to create the object for storing.
            It's handy to use than assiging the one by one.
        */
        //prepare the object to store
        $participants = new Participants();
        $participants->name = $request->name;
        $participants->age = $request->age;
        $participants->guests = $request->guests;
        $participants->date_of_birth = $request->date_of_birth;
        $participants->profession = $request->profession;
        $participants->locality = $request->locality;
        $participants->address = $request->address;
 

        if (auth()->user()->participants()->save($participants))
            return response()->json([
                'success' => true,
                'data' => $participants->toArray()
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Participant not added'
            ], 500);
    }
 
    public function update(Request $request, $id)
    {
        /*
            This function is used to update the existing record.
            Search the participant with their id 
        */

        if (!isset($id) || !is_numeric($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide proper participant id'
            ], 400);
        }
        $participants = auth()->user()->participants()->find($id);
 
        if (!$participants) {
            return response()->json([
                'success' => false,
                'message' => 'Participants not found'
            ], 400);
        }
        $validationsRules = [
            'name' => 'required|max:255',
            'age' => 'required|integer|between:18,55',
            'guests' => 'required|integer|between:0,2',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'profession' => 'required|in:Employed,Student',
            'locality' => 'required|max:255',
            'address' => 'required|min:5|max:50'
        ];
        $validations = [];
        foreach($request->all() as $field => $value){
            $validations[$field] = $validationsRules[$field];
        }
        //Validate the incoming data
        if(!empty($validations)){
            $this->validate($request, $validations);
        }
        $updated = $participants->fill($request->all())->save();
 
        if ($updated)
            return response()->json([
                'success' => true
            ]);
        else
            return response()->json([
                'success' => false,
                'message' => 'Participants can not be updated'
            ], 500);
    }
 
    public function destroy($id)
    {
        //nothing to do here
    }
}
