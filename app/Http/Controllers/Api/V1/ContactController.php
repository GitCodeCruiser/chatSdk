<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Models\ContactList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Resources\ContactListResource;
use App\Http\Requests\User\AddToContactListRequest;
use App\Http\Resources\UnknownContactRepository;

class ContactController extends Controller
{
    public function contactList()
    {
        try {
            $contact_list = ContactList::with(['users'])
                ->where('user_id', auth()->user()->id)
                ->get();

            $contact_list = ContactListResource::collection($contact_list);

            return returnToApi('success', 'Contact List.', ['contact_list' => $contact_list]);
        } catch (\Exception $e) {
            return returnToApi('error', 'Failed to get data.' . ' ' . $e->getMessage());
        }
    }

    public function searchContact(Request $request)
    {
        try {
            $users = User::with('contactLists')
                ->where('name', 'like', '%' . $request->search_text . '%')
                ->get();
            $users = collect($users)
                ->filter(fn ($event) => (is_null($event->contactLists) && ($event->id != auth()->user()->id)))
                ->values();
            $users = UnknownContactRepository::collection($users);
            return returnToApi('success', 'Search List.', ['users' => $users]);
        } catch (\Exception $e) {
            return returnToApi('error', 'Failed to get data.' . ' ' . $e->getMessage());
        }
    }

    public function addToContactList(AddToContactListRequest $request)
    {
        try {
            $exists = ContactList::where(['user_id' => auth()->user()->id, 'contact_id' => $request->contact_id])->exists();

            $message = 'Already in contact list.';
            $return_object = ['is_added' => false];

            if (!$exists) {
                DB::beginTransaction();
                ContactList::create(['user_id' => auth()->user()->id, 'contact_id' => $request->contact_id]);
                DB::commit();
                $message = 'Added to contact list.';
                $return_object = ['is_added' => true];
            }

            return returnToApi('success', $message, $return_object);
        } catch (\Exception $e) {
            DB::rollBack();
            return returnToApi('error', 'Failed to complete registration.' . ' ' . $e->getMessage());
        }
    }
}
