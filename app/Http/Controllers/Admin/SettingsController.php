<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use App\Models\Setting; // Ensure you have a Settings model
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function getStripeSettings()
    {
        $settings = Setting::where('type', 'stripe')->first();
        return response()->json($settings);
    }

    public function updateStripeSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => 'required|string',
            'api_secret' => 'required|string',
            'webhook_secret' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Setting::updateOrCreate(
            ['type' => 'stripe'],
            $request->only('api_key', 'api_secret', 'webhook_secret')
        );

        return response()->json(['message' => 'Stripe settings updated successfully!']);
    }

    public function getPayPalSettings()
    {
        $settings = Setting::where('type', 'paypal')->first();
        return response()->json($settings);
    }

    public function updatePayPalSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'client_id' => 'required|string',
            'client_secret' => 'required|string',
            'mode' => 'required|in:sandbox,live',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Setting::updateOrCreate(
            ['type' => 'paypal'],
            $request->only('client_id', 'client_secret', 'mode')
        );

        return response()->json(['message' => 'PayPal settings updated successfully!']);
    }

    public function getPusherSettings()
    {
        $settings = Setting::where('type', 'pusher')->first();
        return response()->json($settings);
    }

    public function updatePusherSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'app_id' => 'required|string',
            'app_key' => 'required|string',
            'app_secret' => 'required|string',
            'app_cluster' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Setting::updateOrCreate(
            ['type' => 'pusher'],
            $request->only('app_id', 'app_key', 'app_secret', 'app_cluster')
        );

        return response()->json(['message' => 'Pusher settings updated successfully!']);
    }

    public function getMailchimpSettings()
    {
        $settings = Setting::where('type', 'mailchimp')->first();
        return response()->json($settings);
    }

    public function updateMailchimpSettings(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'api_key' => 'required|string',
            'list_id' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        Setting::updateOrCreate(
            ['type' => 'mailchimp'],
            $request->only('api_key', 'list_id')
        );

        return response()->json(['message' => 'Mailchimp settings updated successfully!']);
    }
}
