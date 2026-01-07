<?php

namespace App\Services;

use App\Models\User;

class NotificationService
{
    private SmsService $smsService;

    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Send order confirmation via SMS & WhatsApp
     */
    public function notifyOrderConfirmation($order)
    {
        $customer = $order->user;
        $package = $order->package;

        if (!$customer->phone) {
            return false;
        }

        $phoneNumber = SmsService::formatPhoneNumber($customer->phone);
        
        $data = [
            'order_id' => $order->id,
            'package_name' => $package->name,
            'event_date' => $order->event_date->format('d/m/Y'),
            'total_price' => number_format($order->total_price, 0),
        ];

        // Send via WhatsApp first (preferred), fallback to SMS
        try {
            if ($customer->prefer_whatsapp ?? true) {
                $sent = $this->smsService->sendWhatsAppTemplate($phoneNumber, 'order_confirmation', $data);
                if (!$sent) {
                    $sent = $this->smsService->sendSmsTemplate($phoneNumber, 'order_confirmation', $data);
                }
                return $sent;
            } else {
                return $this->smsService->sendSmsTemplate($phoneNumber, 'order_confirmation', $data);
            }
        } catch (\Exception $e) {
            \Log::error('Order confirmation notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment reminder
     */
    public function notifyPaymentReminder($order, $paymentUrl = null)
    {
        $customer = $order->user;

        if (!$customer->phone) {
            return false;
        }

        $phoneNumber = SmsService::formatPhoneNumber($customer->phone);

        $data = [
            'order_id' => $order->id,
            'total_price' => number_format($order->total_price, 0),
            'payment_link' => $paymentUrl ?? route('customer.orders.show', $order),
        ];

        try {
            if ($customer->prefer_whatsapp ?? true) {
                $sent = $this->smsService->sendWhatsAppTemplate($phoneNumber, 'payment_reminder', $data);
                if (!$sent) {
                    $sent = $this->smsService->sendSmsTemplate($phoneNumber, 'payment_reminder', $data);
                }
                return $sent;
            } else {
                return $this->smsService->sendSmsTemplate($phoneNumber, 'payment_reminder', $data);
            }
        } catch (\Exception $e) {
            \Log::error('Payment reminder notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send payment confirmation
     */
    public function notifyPaymentConfirmation($payment)
    {
        $order = $payment->order;
        $customer = $order->user;

        if (!$customer->phone) {
            return false;
        }

        $phoneNumber = SmsService::formatPhoneNumber($customer->phone);

        $data = [
            'order_id' => $order->id,
            'amount' => number_format($payment->amount, 0),
        ];

        try {
            if ($customer->prefer_whatsapp ?? true) {
                $sent = $this->smsService->sendWhatsAppTemplate($phoneNumber, 'payment_confirmation', $data);
                if (!$sent) {
                    $sent = $this->smsService->sendSmsTemplate($phoneNumber, 'payment_confirmation', $data);
                }
                return $sent;
            } else {
                return $this->smsService->sendSmsTemplate($phoneNumber, 'payment_confirmation', $data);
            }
        } catch (\Exception $e) {
            \Log::error('Payment confirmation notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send event reminder (3 days before)
     */
    public function notifyEventReminder3Days($order)
    {
        $customer = $order->user;

        if (!$customer->phone) {
            return false;
        }

        $phoneNumber = SmsService::formatPhoneNumber($customer->phone);

        $data = [
            'event_date' => $order->event_date->format('d F Y'),
            'event_location' => $order->event_location ?? 'TBD',
        ];

        try {
            if ($customer->prefer_whatsapp ?? true) {
                $sent = $this->smsService->sendWhatsAppTemplate($phoneNumber, 'event_reminder_3days', $data);
                if (!$sent) {
                    $sent = $this->smsService->sendSmsTemplate($phoneNumber, 'event_reminder_3days', $data);
                }
                return $sent;
            } else {
                return $this->smsService->sendSmsTemplate($phoneNumber, 'event_reminder_3days', $data);
            }
        } catch (\Exception $e) {
            \Log::error('Event reminder 3 days notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send event reminder (1 day before)
     */
    public function notifyEventReminder1Day($order, $teamInfo = 'Tim profesional kami')
    {
        $customer = $order->user;

        if (!$customer->phone) {
            return false;
        }

        $phoneNumber = SmsService::formatPhoneNumber($customer->phone);

        $data = [
            'event_date' => $order->event_date->format('d F Y'),
            'event_location' => $order->event_location ?? 'TBD',
            'team_info' => $teamInfo,
        ];

        try {
            if ($customer->prefer_whatsapp ?? true) {
                $sent = $this->smsService->sendWhatsAppTemplate($phoneNumber, 'event_reminder_1day', $data);
                if (!$sent) {
                    $sent = $this->smsService->sendSmsTemplate($phoneNumber, 'event_reminder_1day', $data);
                }
                return $sent;
            } else {
                return $this->smsService->sendSmsTemplate($phoneNumber, 'event_reminder_1day', $data);
            }
        } catch (\Exception $e) {
            \Log::error('Event reminder 1 day notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send event completed notification
     */
    public function notifyEventCompleted($order)
    {
        $customer = $order->user;

        if (!$customer->phone) {
            return false;
        }

        $phoneNumber = SmsService::formatPhoneNumber($customer->phone);

        try {
            if ($customer->prefer_whatsapp ?? true) {
                $sent = $this->smsService->sendWhatsAppTemplate($phoneNumber, 'event_completed');
                if (!$sent) {
                    $sent = $this->smsService->sendSmsTemplate($phoneNumber, 'event_completed');
                }
                return $sent;
            } else {
                return $this->smsService->sendSmsTemplate($phoneNumber, 'event_completed');
            }
        } catch (\Exception $e) {
            \Log::error('Event completed notification error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Send review thank you message
     */
    public function notifyReviewThankYou($review)
    {
        $customer = $review->user;

        if (!$customer->phone) {
            return false;
        }

        $phoneNumber = SmsService::formatPhoneNumber($customer->phone);

        try {
            if ($customer->prefer_whatsapp ?? true) {
                $sent = $this->smsService->sendWhatsAppTemplate($phoneNumber, 'review_thank_you');
                if (!$sent) {
                    $sent = $this->smsService->sendSmsTemplate($phoneNumber, 'review_thank_you');
                }
                return $sent;
            } else {
                return $this->smsService->sendSmsTemplate($phoneNumber, 'review_thank_you');
            }
        } catch (\Exception $e) {
            \Log::error('Review thank you notification error: ' . $e->getMessage());
            return false;
        }
    }
}
