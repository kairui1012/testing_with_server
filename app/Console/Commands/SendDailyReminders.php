<?php
// app/Console/Commands/SendDailyReminders.php
namespace App\Console\Commands;

use App\Models\DailyReminder;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendDailyReminders extends Command
{
    protected $signature = 'reminders:send';
    protected $description = 'Check for scheduled daily reminders and send them via WhatsApp.';

    public function handle()
    {
        $now = now();
        // Use dayOfWeekIso (1 = Monday, 7 = Sunday) to match our database schema
        $currentDay = $now->dayOfWeekIso;
        $currentTime = $now->format('H:i');

        $this->info("Checking for reminders for Day: $currentDay at Time: $currentTime...");

        // Find all active reminders for the current day and time
        $reminders = DailyReminder::where('is_active', true)
            ->where('weekday', $currentDay)
            ->whereTime('time', $currentTime)
            ->with('user') // Eager load user to get phone number
            ->get();

        if ($reminders->isEmpty()) {
            $this->info('No reminders to send at this time.');
            return;
        }

        $this->info("Found {$reminders->count()} reminders to send.");

        $defaultMessage = "Do the following task\nOPEN \"ENJOY\" APP\nCHECK IN\nPLAY/VIEW VIDEO";

        foreach ($reminders as $reminder) {
            $user = $reminder->user;

            // Ensure user and phone number exist
            if (!$user || !$user->phone) {
                Log::warning("Skipping reminder ID {$reminder->id}: User or phone number is missing.");
                continue;
            }

            // Determine the message content
            $messageToSend = $reminder->message ?: $defaultMessage;

            //sentinel phone number to whatsapp format

            $user->phone = preg_replace('/\D/', '', $user->phone); // Remove non-numeric characters
            if (strlen($user->phone) < 10) {
                $this->error("Invalid phone number for user ID {$user->id}. Skipping reminder.");
                Log::warning("Invalid phone number for user ID {$user->id}. Skipping reminder.");
                continue;
            }

            //if phone number start with 0, remove it
            if (substr($user->phone, 0, 1) === '0') {
                $user->phone = substr($user->phone, 1);
            }
            //if phone number not start with 60, add it
            if (substr($user->phone, 0, 2) !== '60') {
                $user->phone = '60' . $user->phone;
            }

            // echo the phone number for debugging
            $this->info("Sending reminder to user ID {$user->id} with phone number {$user->phone}.");

            // Construct the API payload
            $chatId = $user->phone . '@c.us';
            $wahaApiUrl = config('services.waha.url');
            $wahaSession = config('services.waha.session');

            try {

                //log the sending parameter
                Log::info("Sending WAHA reminder to {$chatId} with message: {$messageToSend}");
                //log the waha api url and session
                Log::info("WAHA API URL: {$wahaApiUrl}, Session: {$wahaSession}");

                $response = Http::post("{$wahaApiUrl}/api/sendText", [
                    'chatId' => $chatId,
                    'text' => $messageToSend,
                    'session' => $wahaSession,
                ]);



                if ($response->successful()) {
                    $this->info("Successfully sent reminder to {$chatId}.");
                    Log::info("WAHA Reminder Sent: ", ['chatId' => $chatId, 'response' => $response->json()]);
                } else {
                    $this->error("Failed to send reminder to {$chatId}.");
                    Log::error("WAHA API Error: ", ['status' => $response->status(), 'response' => $response->body()]);
                }

            } catch (\Exception $e) {
                $this->error("An exception occurred while sending to {$chatId}: " . $e->getMessage());
                Log::critical("WAHA API Exception: ", ['message' => $e->getMessage()]);
            }
        }

        $this->info('Finished sending reminders.');
    }
}
