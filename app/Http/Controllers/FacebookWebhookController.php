<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Booking;

class FacebookWebhookController extends Controller
{
    // Verification handshake for Facebook webhook
    public function verify(Request $request)
    {
        $verify_token = env('FACEBOOK_VERIFY_TOKEN');

        if ($request->get('hub_mode') === 'subscribe' && $request->get('hub_verify_token') === $verify_token) {
            return response($request->get('hub_challenge'));
        }

        return response('Invalid verify token', 403);
    }

    // Receive webhook events
    public function receive(Request $request)
    {
        $payload = $request->all();

        if (!empty($payload['entry'])) {
            foreach ($payload['entry'] as $entry) {
                if (!empty($entry['messaging'])) {
                    foreach ($entry['messaging'] as $event) {
                        // Referral from m.me link
                        if (!empty($event['referral'])) {
                            $senderPsid = $event['sender']['id'] ?? null;
                            $ref = $event['referral']['ref'] ?? null; // e.g., booking_123
                            if ($senderPsid && $ref && str_starts_with($ref, 'booking_')) {
                                $bookingId = intval(str_replace('booking_', '', $ref));
                                $booking = Booking::find($bookingId);
                                if ($booking) {
                                    // store psid with booking
                                    $booking->messenger_psid = $senderPsid;
                                    $booking->save();

                                    // send confirmation message (prefer DB-stored settings if env missing)
                                    $settings = \App\Models\FacebookSetting::first();
                                    $token = $settings->page_access_token ?? env('FACEBOOK_PAGE_ACCESS_TOKEN');
                                    if ($token) {
                                        $this->sendBookingConfirmation($senderPsid, $booking);
                                    } else {
                                        \Log::warning('No page access token available to send confirmation.');
                                    }
                                }
                            }
                        }

                        // If user sends a direct message, you can capture PSID here too
                        if (!empty($event['message']) && !empty($event['sender']['id'])) {
                            // Optional: handle regular messages
                        }
                    }
                }
            }
        }

        return response()->json(['status' => 'ok']);
    }

    protected function sendBookingConfirmation(string $psid, Booking $booking)
    {
        $token = env('FACEBOOK_PAGE_ACCESS_TOKEN');

        if (! $token) {
            \Log::warning('Facebook page access token missing; cannot send confirmation message.');
            return;
        }

        $text = "Thanks {$booking->client_name}! Your booking (ID: {$booking->id}) for {$booking->service->name} on {$booking->booking_datetime} is received. See you soon!";

        $url = "https://graph.facebook.com/v15.0/me/messages?access_token={$token}";

        $payload = [
            'recipient' => ['id' => $psid],
            'message' => ['text' => $text],
            // use message tag for confirmed events
            'messaging_type' => 'MESSAGE_TAG',
            'tag' => 'CONFIRMED_EVENT_UPDATE'
        ];

        $res = Http::post($url, $payload);

        if ($res->failed()) {
            \Log::error('FB confirmation failed', ['body' => $res->body()]);
        }
    }
}
