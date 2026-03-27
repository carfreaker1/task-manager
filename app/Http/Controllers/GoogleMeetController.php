<?php

namespace App\Http\Controllers;

use App\Models\GoogleToken;
use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;
use Illuminate\Http\Request;

class GoogleMeetController extends Controller
{

    private function getClient()
    {
        $client = new Client();

        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(env('GOOGLE_REDIRECT_URI'));

        $client->addScope(Calendar::CALENDAR);
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        return $client;
    }

    public function redirectToGoogle()
    {
        $client = $this->getClient();

        return redirect($client->createAuthUrl());
    }

    public function handleGoogleCallback(Request $request)
    {
        $client = $this->getClient();

        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        // session(['google_token' => $token]);
        GoogleToken::updateOrCreate(
            ['id' => 1],
            ['token' => json_encode($token)]
        );

        return redirect('/create-meeting');
    }

    // public function createMeeting()
    // {
    //     $client = $this->getClient();

    //     if (!session()->has('google_token')) {
    //         return redirect('/google-auth');
    //     }

    //     $client->setAccessToken(session('google_token'));

    //     if ($client->isAccessTokenExpired()) {
    //         return redirect('/google-auth');
    //     }

    //     $service = new Calendar($client);

    //     $event = new Event([
    //         'summary' => 'Task Meeting',
    //         'start' => [
    //             'dateTime' => '2026-03-20T10:00:00+05:30',
    //             'timeZone' => 'Asia/Kolkata',
    //         ],
    //         'end' => [
    //             'dateTime' => '2026-03-20T11:00:00+05:30',
    //             'timeZone' => 'Asia/Kolkata',
    //         ],
    //         'conferenceData' => [
    //             'createRequest' => [
    //                 'requestId' => uniqid(),
    //                 'conferenceSolutionKey' => [
    //                     'type' => 'hangoutsMeet'
    //                 ]
    //             ]
    //         ]
    //     ]);

    //     $event = $service->events->insert(
    //         'primary',
    //         $event,
    //         ['conferenceDataVersion' => 1]
    //     );

    //     return "Meet Link: " . $event->hangoutLink;
    // }

    public function createMeetingDynamic($start, $end, $summary = 'Task Meeting')
    {
        $client = $this->getClient();

        // if (!session()->has('google_token')) {
        //     return redirect('/google-auth');
        // }

        $token = GoogleToken::first();

        if (!$token) {
            return null;
        }
        $client->setAccessToken(json_decode($token->token, true));
        // $client->setAccessToken(session('google_token'));
        $service = new Calendar($client);

        $event = new Event([
            'summary' => $summary,
            'start' => [
                'dateTime' => $start,
                'timeZone' => 'Asia/Kolkata',
            ],
            'end' => [
                'dateTime' => $end,
                'timeZone' => 'Asia/Kolkata',
            ],
            'conferenceData' => [
                'createRequest' => [
                    'requestId' => uniqid(),
                    'conferenceSolutionKey' => [
                        'type' => 'hangoutsMeet'
                    ]
                ]
            ]
        ]);

        $event = $service->events->insert(
            'primary',
            $event,
            ['conferenceDataVersion' => 1]
        );

        return $event->hangoutLink;
    }
}
