<?php
/**
 * Created by PhpStorm.
 * User: Kenyroot
 * Date: 30.07.2020
 * Time: 13:49
 */

namespace App\Http\Controllers;


use App\Models\Event;
use App\Models\Member;
use Illuminate\Http\Request;
use App\Http\Resources\MemberResource as MemberResource;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ApiMemberController extends Controller
{
    public function get(Request $request){
        $eventId = $request->input('event_id');
        $members = Member::query();
        if($eventId){
            $event = Event::find($eventId);
            if(!$event){
                return response()->json([
                    'error' => 'EVENT_ID_NOT_EXISTS'
                ], 400);
            }
            $members = $members->where('event_id',$event->id);
        }
        $members = $members->get(); //TODO: фильтр
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
            'first_name.min' => 'LIMIT_MIN_FIRST_NAME',
            'first_name.max' => 'LIMIT_MAX_FIRST_NAME',
            'last_name.min' => 'LIMIT_MIN_LAST_NAME',
            'last_name.max' => 'LIMIT_MAX_LAST_NAME',
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
        $id = $request->id;
        $member = Member::find($id);
        if(!$member){
            return response()->json([
                'error' => 'ID_NOT_EXISTS'
            ], 400);
        }
        $member->delete();
        return response()->json(['error'=>'FALSE','message'=>'member id='.$id.' deleted']);
    }
    public function update(Request $request){

        // тут будет обновление last_name и first_name, имейл обычно не разрешают изменять
        $validator = Validator::make($request->all(), [ // TODO: можно вынести в свой Request
//            'id' => 'required|exists:members,id',
            'first_name' => 'string|min:3|max:512',
            'last_name' => 'string|min:3|max:512',
        ], [
//            'id.exists' => 'ID_NOT_EXISTS',
//            'id.required' => 'REQUIRED_ID',
            'first_name.min' => 'LIMIT_MIN_FIRST_NAME',
            'first_name.max' => 'LIMIT_MAX_FIRST_NAME',
            'last_name.min' => 'LIMIT_MIN_LAST_NAME',
            'last_name.max' => 'LIMIT_MAX_LAST_NAME',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()->first()
            ], 400);
        }

        $firstName = $request->input('first_name');
        $lastName = $request->input('last_name');
        $id = $request->id;
        $member = Member::find($id);
        if(!$member){
            return response()->json([
                'error' => 'ID_NOT_EXISTS'
            ], 400);
        }
        if($firstName){
            $member->first_name = $firstName;
        }
        if($lastName){
            $member->last_name = $lastName;
        }
        $member->save();
        return new MemberResource($member);
    }

    public function addMemberToEvent(Request $request){
        $memberId = $request->member_id;
        $member = Member::find($memberId);
        if(!$member){
            return response()->json([
                'error' => 'MEMBER_ID_NOT_EXISTS'
            ], 400);
        }
        $eventId = $request->event_id;
        $event = Event::find($eventId);
        if(!$event){
            return response()->json([
                'error' => 'EVENT_ID_NOT_EXISTS'
            ], 400);
        }
        $member->event_id = $eventId;
        $member->save();
        return response()->json(['STATUS'=>'OK','message'=>'member id='.$member->id.' added event id='.$event->id]);
    }
}