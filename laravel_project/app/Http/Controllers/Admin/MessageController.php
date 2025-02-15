<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Item;
use App\ThreadItem;
use App\User;
use Artesaos\SEOTools\Facades\SEOMeta;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use Cmgmyr\Messenger\Models\Thread;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\View\View;
use Carbon\Carbon;

class MessageController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function index(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.message.messages', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $user_id = empty($request->user_id) ? 0 : $request->user_id;
        $all_users = User::orderBy('name')->get();

        if($user_id > 0)
        {
            // All threads that user is participating in
            $threads = Thread::forUser($user_id)->latest('updated_at')->get();
        }
        else
        {
            // All threads, ignore deleted/archived participants
            $threads = Thread::getAllLatest()->get();
        }

        // All threads that user is participating in, with new messages
        // $threads = Thread::forUserWithNewMessages(Auth::id())->latest('updated_at')->get();

        return view('backend.admin.message.index', compact('all_users', 'user_id', 'threads'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.message.create-message', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $item_id = $request->item;

        $item = Item::findOrFail($item_id);

        if($item->user_id == Auth::user()->id)
        {
            \Session::flash('flash_message', __('alert.message-send-error-yourself'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.messages.index');
        }
        else
        {
            return view('backend.admin.message.create', compact('item'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return RedirectResponse
     * @throws Exception
     */
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|max:255',
            'message' => 'required',
            'recipient' => 'required|numeric',
            'item' => 'required|numeric',
        ]);

        $subject = $request->subject;
        $message = $request->message;
        $recipient_user_id = $request->recipient;
        $item_id = $request->item;

        $recipient_user_id_exist = User::find($recipient_user_id);
        if(empty($recipient_user_id_exist))
        {
            \Session::flash('flash_message', __('alert.message-send-error-recipient'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.messages.index');
        }

        $item_id_exist = Item::find($item_id);
        if(empty($item_id_exist))
        {
            \Session::flash('flash_message', __('alert.message-send-error-listing'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.messages.index');
        }
        if($item_id_exist->user_id != $recipient_user_id_exist->id)
        {
            \Session::flash('flash_message', __('alert.message-send-error-not-match'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('admin.messages.index');
        }

        $thread = Thread::create([
            'subject' => $subject,
        ]);

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::user()->id,
            'body' => $message,
        ]);

        // Sender
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' => Auth::user()->id,
            'last_read' => new Carbon,
        ]);

        // Recipients
        $thread->addParticipant($recipient_user_id);

        // Thread Item relation model
        ThreadItem::create([
            'thread_id' => $thread->id,
            'item_id' => $item_id_exist->id,
        ]);

        \Session::flash('flash_message', __('alert.message-send'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.messages.show', $thread->id);
    }

    /**
     * Display the specified resource.
     *
     * @param int $thread_id
     * @return Factory|View
     */
    public function show(int $thread_id)
    {
        $settings = app('site_global_settings');

        /**
         * Start SEO
         */
        SEOMeta::setTitle(__('seo.backend.admin.message.show-message', ['site_name' => empty($settings->setting_site_name) ? config('app.name', 'Laravel') : $settings->setting_site_name]));
        SEOMeta::setDescription('');
        SEOMeta::setCanonical(URL::current());
        SEOMeta::addKeyword($settings->setting_site_seo_home_keywords);
        /**
         * End SEO
         */

        $thread = Thread::findOrFail($thread_id);

        $thread_item = ThreadItem::where('thread_id', $thread->id)->first();
        $item = $thread_item->item()->first();

        // show current user in list if not a current participant
        // $users = User::whereNotIn('id', $thread->participantsUserIds())->get();

        // don't show the current user in list
        $login_user = Auth::user();
        //$all_users = User::whereNotIn('id', $thread->participantsUserIds($login_user->id))->get();
        //$all_users = User::whereIn('id', $thread->participantsUserIds())->get();

        if($thread->hasParticipant($login_user->id))
        {
            $thread->markAsRead($login_user->id);
        }

        return view('backend.admin.message.show', compact('thread', 'item'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $thread_id
     * @return RedirectResponse
     */
    public function edit(int $thread_id)
    {
        return redirect()->route('admin.messages.show', $thread_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $thread_id
     * @return RedirectResponse
     * @throws Exception
     */
    public function update(Request $request, int $thread_id)
    {
        $request->validate([
            'message' => 'required',
        ]);

        $thread = Thread::findOrFail($thread_id);
        $message = $request->message;
        $login_user = Auth::user();

        if(!$thread->hasParticipant($login_user->id))
        {
            \Session::flash('flash_message', __('alert.message-reply-error-own'));
            \Session::flash('flash_type', 'danger');

            return redirect()->route('user.messages.index');
        }

        // Message
        Message::create([
            'thread_id' => $thread->id,
            'user_id' => $login_user->id,
            'body' => $message,
        ]);

        // Add replier as a participant
        $participant = Participant::firstOrCreate([
            'thread_id' => $thread->id,
            'user_id' => $login_user->id,
        ]);
        $participant->last_read = new Carbon;
        $participant->save();

        \Session::flash('flash_message', __('alert.message-send'));
        \Session::flash('flash_type', 'success');

        return redirect()->route('admin.messages.show', $thread->id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $thread_id
     * @return RedirectResponse
     */
    public function destroy(int $thread_id)
    {
        return redirect()->route('admin.messages.show', $thread_id);
    }
}
