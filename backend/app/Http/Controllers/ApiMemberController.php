<?php
/**
 * Created by PhpStorm.
 * User: Kenyroot
 * Date: 30.07.2020
 * Time: 13:49
 */

namespace App\Http\Controllers;


use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Resources\MemberResource as MemberResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ApiMemberController extends Controller
{
    public function get(Request $request){
        $members = Member::all(); //TODO: фильтр
        return MemberResource::collection($members);
    }
    public function add(Request $request){
        $validator = Validator::make($request->all(), [ // TODO: можно вынести в свой Request
            'email' => 'required|email|unique:members,email',
            'first_name' => 'required|string|min:3|max:512',
            'last_name' => 'required|string|min:3|max:512',
        ], [
            'email.unique' => 'EXISTS_EMAIL',
            'email.email'  => 'INCORRECT_FORMAT_EMAIL',
            'email.required' => 'EMAIL_REQUIRED',
            'first_name.required' => 'REQUIRED_FIRST_NAME',
            'last_name.required' => 'REQUIRED_LAST_NAME',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }
        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $email = $request->input('email');
        $member = new Member();
        $member->first_name = $firstName;
        $member->last_name = $lastName;
        $member->email = $email;
        $member->save();
        // отправляем письмо
//        Mail::to($member->email)->queue(new CreateMember($member)); // TODO
        Log::info('ApiMemberController->add()',['member'=> new MemberResource($member)]);
        return new MemberResource($member);

    }
    public function delete(Request $request){
        $validator = Validator::make($request->all(), [ // TODO: можно вынести в свой Request
            'id' => 'required|exists:members,id',
        ], [
            'id.exists' => 'ID_NOT_EXISTS',
            'id.required' => 'REQUIRED_ID',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }
        $id = $request->input('id');
        $member = Member::find($id);
        $member->delete();
        return response()->json(['error'=>'FALSE','message'=>'member id='.$id.' deleted']);
    }
    public function update(Request $request){

    }
}