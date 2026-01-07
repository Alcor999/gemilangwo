<?php

namespace App\Services;

use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class SmsService
{
    private ?Client $twilio = null;
    private bool $isConfigured = false;

    public function __construct()
    {
        $accountSid = config('services.twilio.account_sid');
        $authToken = config('services.twilio.auth_token');
        
        // Only initialize Twilio if credentials are configured
        if ($accountSid && $authToken) {
            try {
                $this->twilio = new Client($accountSid, $authToken);
                $this->isConfigured = true;
            } catch (\Exception $e) {
                \Log::warning('Twilio initialization failed: ' . $e->getMessage());
                $this->isConfigured = false;
            }
        } else {
            \Log::warning('Twilio credentials not configured in .env');
            $this->isConfigured = false;
        }
    }

    /**
     * Check if Twilio is configured
     */
    public function isConfigured(): bool
    {
        return $this->isConfigured;
    }

    /**
     * Send SMS via Twilio
     */
    public function sendSms(string $phoneNumber, string $message): bool
    {
        // If Twilio not configured, log and return false
        if (!$this->isConfigured()) {
            \Log::info('SMS not sent - Twilio not configured. To: ' . $phoneNumber . ' Message: ' . substr($message, 0, 50) . '...');
            return false;
        }

        try {
            $this->twilio->messages->create(
                $phoneNumber,
                [
                    'from' => config('services.twilio.phone_number'),
                    'body' => $message,
                ]
            );

            return true;
        } catch (TwilioException $e) {
            \Log::error('SMS Error: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            \Log::error('SMS Error (General): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send WhatsApp message via Twilio
     */
    public function sendWhatsApp(string $phoneNumber, string $message): bool
    {
        // If Twilio not configured, log and return false
        if (!$this->isConfigured()) {
            \Log::info('WhatsApp not sent - Twilio not configured. To: ' . $phoneNumber . ' Message: ' . substr($message, 0, 50) . '...');
            return false;
        }

        try {
            $this->twilio->messages->create(
                'whatsapp:' . $phoneNumber,
                [
                    'from' => 'whatsapp:' . config('services.twilio.whatsapp_number'),
                    'body' => $message,
                ]
            );

            return true;
        } catch (TwilioException $e) {
            \Log::error('WhatsApp Error: ' . $e->getMessage());
            return false;
        } catch (\Exception $e) {
            \Log::error('WhatsApp Error (General): ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send SMS with template
     */
    public function sendSmsTemplate(string $phoneNumber, string $templateKey, array $data = []): bool
    {
        $message = $this->renderTemplate($templateKey, $data);
        return $this->sendSms($phoneNumber, $message);
    }

    /**
     * Send WhatsApp with template
     */
    public function sendWhatsAppTemplate(string $phoneNumber, string $templateKey, array $data = []): bool
    {
        $message = $this->renderTemplate($templateKey, $data);
        return $this->sendWhatsApp($phoneNumber, $message);
    }

    /**
     * Render message template
     */
    private function renderTemplate(string $templateKey, array $data = []): string
    {
        $templates = [
            'order_confirmation' => "Pesanan Anda telah diterima!\n\nNo. Pesanan: {order_id}\nPaket: {package_name}\nTanggal Event: {event_date}\nTotal: Rp {total_price}\n\nTerima kasih telah memilih kami! 🎉",

            'payment_reminder' => "Pengingat Pembayaran 💰\n\nNo. Pesanan: {order_id}\nTotal: Rp {total_price}\n\nSegera lakukan pembayaran untuk mengamankan tanggal event Anda.\n\nLink pembayaran: {payment_link}",

            'payment_confirmation' => "Pembayaran Berhasil ✅\n\nNo. Pesanan: {order_id}\nJumlah: Rp {amount}\n\nTerima kasih! Event Anda sudah dikonfirmasi.",

            'event_reminder_3days' => "Pengingat Event 📅\n\nEvent Anda tinggal 3 hari lagi!\n\nTanggal: {event_date}\nLokasi: {event_location}\n\nSiap-siap untuk hari istimewa Anda! 🎊",

            'event_reminder_1day' => "Pengingat Event Akhir 📅\n\nEvent Anda besok!\n\nTanggal: {event_date}\nLokasi: {event_location}\nTim: {team_info}\n\nSee you tomorrow! 🎉",

            'event_completed' => "Event Selesai! 🎊\n\nTerima kasih atas kepercayaan Anda!\nKami harap event Anda sempurna.\n\nBerikan rating & review untuk event Anda di aplikasi.",

            'review_thank_you' => "Terima Kasih atas Review ⭐\n\nReview Anda membantu kami untuk terus meningkatkan kualitas layanan.\n\nHarga spesial untuk booking berikutnya! 🎁",
        ];

        $message = $templates[$templateKey] ?? "Halo, ada pesan untuk Anda.";

        foreach ($data as $key => $value) {
            $message = str_replace('{' . $key . '}', $value, $message);
        }

        return $message;
    }

    /**
     * Format phone number to E.164 format
     * +62812345678 or 62812345678 or 0812345678
     */
    public static function formatPhoneNumber(string $phone): string
    {
        // Remove any non-digit characters
        $phone = preg_replace('/\D/', '', $phone);

        // Handle Indonesian numbers
        if (substr($phone, 0, 1) === '0') {
            $phone = '62' . substr($phone, 1);
        } elseif (substr($phone, 0, 2) !== '62') {
            $phone = '62' . $phone;
        }

        return '+' . $phone;
    }
}
