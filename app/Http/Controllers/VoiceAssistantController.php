<?php

// Laravel controller to handle all Dialogflow intents and Twilio webhook

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VoiceAssistantController extends Controller
{
    public function startCall()
    {
        return response()->json([
            'phone' => '+1 940 289 8174' // Using your Twilio number
        ]);
    }

    public function handleDialogflow(Request $request)
    {
        $intent = $request->input('fulfillmentInfo.displayName');
        $params = $request->input('sessionInfo.parameters');

        Log::info("Intent: $intent", $params);

        switch ($intent) {
            case 'place.order':
                $item = $params['item'] ?? 'something delicious';
                // You'd add the item to the cart here
                return $this->respond("Okay, adding $item to your cart.");

            case 'add.item':
                $item = $params['item'] ?? 'an item';
                // Add to cart
                return $this->respond("$item added! Let me know when you're ready to checkout.");

            case 'select.restaurant':
                $restaurant = $params['restaurant'] ?? 'a place';
                // Assign restaurant
                return $this->respond("Got it. Selected $restaurant.");

            case 'checkout.order':
                // Create the order in your DB
                return $this->respond("Your order is confirmed! Delivery in 25 minutes.");

            case 'track.order':
                return $this->respond("Your delivery partner Akshay is 2 km away and will arrive in 7 minutes.");

            case 'login.google':
                $email = 'user@example.com';
                Mail::raw('Click here to login: https://biterush.com/login-link', function ($message) use ($email) {
                    $message->to($email)->subject('BiteRush Google Login Link');
                });
                return $this->respond("I\'ve sent a Google login link to your email.");

            case 'cancel.order':
                return $this->respond("Your order has been canceled. Hope to see you again soon!");

            default:
                return $this->respond("Sorry, I didn't understand that. Try saying 'Order Butter Chicken' or 'Track my order'.");
        }
    }

    private function respond($text)
    {
        return response()->json([
            'fulfillment_response' => [
                'messages' => [
                    [
                        'text' => [
                            'text' => [$text]
                        ]
                    ]
                ]
            ]
        ]);
    }

    // Fallback Twilio webhook
    public function twilioWebhook(Request $request)
    {
        $response = new \SimpleXMLElement('<Response></Response>');
        $say = $response->addChild('Say', 'Hi, this is your BiteRush assistant. Please wait while we connect you.');
        $say->addAttribute('voice', 'Polly.Joanna');

        return response($response->asXML(), 200)->header('Content-Type', 'text/xml');
    }
}
