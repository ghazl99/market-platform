<?php

namespace Modules\User\Http\Controllers\Auth\Customer;

use Carbon\Carbon;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Stevebauman\Location\Facades\Location;
use Modules\User\Services\Auth\ProfileService;
use Modules\User\Http\Requests\Auth\ProfileUpdateRequest;

class ProfileController extends Controller
{

    public function __construct(
        protected ProfileService $profileService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();

        // إذا تستخدم جدول user_sessions
        $activeSessionsCount = DB::table('sessions')
            ->where('user_id', Auth::user()->id)
            ->count();

        return view('themes.' . current_theme_name_en() . '.security', compact('activeSessionsCount'));
    }
    public function activeSessions()
    {
        $sessions = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->orderByDesc('last_activity')
            ->get()
            ->map(function ($session) {
                $session->last_activity = Carbon::createFromTimestamp($session->last_activity)->diffForHumans();

                // استخدم مكتبة agent لاستخراج النظام والمتصفح
                $agent = new Agent();
                $agent->setUserAgent($session->user_agent);
                $session->browser = $agent->browser();
                $session->os = $agent->platform();

                // استخدم مكتبة Location لمعرفة الدولة/المدينة من IP
                $position = Location::get($session->ip_address);
                $session->location = $position
                    ? ($position->cityName . ', ' . $position->countryName)
                    : 'غير معروف';

                return $session;
            });

        return view('themes.' . current_theme_name_en() . '.sessions', compact('sessions'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {

        return view('themes.' . current_theme_name_en() . '.profile');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProfileUpdateRequest $request, $id)
    {
        $user = Auth::user();

        $this->profileService->updateProfile($user, $request->validated());

        return  redirect()->route('auth.profile.edit', $user->id)
            ->with('success', __('Updated successfully'));
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $deleted = DB::table('sessions')
            ->where('user_id', Auth::id())
            ->where('id', $id)
            ->delete();

        return back()->with('success', 'تم تسجيل الخروج من هذا الجهاز.');
    }
}
