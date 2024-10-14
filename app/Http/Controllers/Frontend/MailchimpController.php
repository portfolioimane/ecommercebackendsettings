<?php
namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Subscriber; // Import the Subscriber model

class MailchimpController extends Controller
{
    private $apiKey;
    private $listId;

    public function __construct()
    {
        $this->apiKey = config('services.mailchimp.api_key');
        $this->listId = config('services.mailchimp.list_id');

        Log::info('Current Environment: ' . app()->environment());
        Log::info('Mailchimp API Key: ' . $this->apiKey);
        Log::info('Mailchimp List ID: ' . $this->listId);
    }

    public function subscribe(Request $request)
    {
        // Validate email input
        $request->validate([
            'email' => 'required|email',
        ]);

        $email = $request->input('email');

        // Extract the data center from the API key
        $dataCenter = $this->getDataCenterFromApiKey($this->apiKey);

        // Construct the Mailchimp API URL
        $url = "https://{$dataCenter}.api.mailchimp.com/3.0/lists/{$this->listId}/members";

        // Send subscription request to Mailchimp
        $response = $this->subscribeToMailchimp($url, $email);

        // Handle the response
        if ($response->successful()) {
            // Save the subscriber to the local database
            $this->storeSubscriberInDatabase($email, 'subscribed');

            return response()->json([
                'success' => true,
                'message' => 'Successfully subscribed!',
            ]);
        }

        // If subscription failed, log the error and return response
        return $this->handleResponse($response);
    }

    private function getDataCenterFromApiKey(string $apiKey): string
    {
        return substr($apiKey, strpos($apiKey, '-') + 1);
    }

    private function subscribeToMailchimp(string $url, string $email)
    {
        return Http::withBasicAuth('anystring', $this->apiKey)
            ->post($url, [
                'email_address' => $email,
                'status' => 'subscribed',
            ]);
    }

    private function handleResponse($response)
    {
        Log::info('Mailchimp Response: ', [
            'status' => $response->status(),
            'body' => $response->json(),
        ]);

        if ($response->failed()) {
            return response()->json([
                'success' => false,
                'message' => 'Error subscribing to Mailchimp: ' . $response->body(),
                'error' => $response->json()
            ], 500);
        }
    }

    private function storeSubscriberInDatabase($email, $status)
    {
        Subscriber::create([
            'email' => $email,
            'status' => $status,
        ]);
    }
}
