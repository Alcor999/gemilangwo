<?php

namespace App\Traits;

use App\Services\NotificationService;
use App\Services\SmsService;

trait SendsNotifications
{
    /**
     * Get notification service instance
     */
    protected function getNotificationService(): NotificationService
    {
        return app(NotificationService::class);
    }

    /**
     * Send order confirmation SMS/WhatsApp
     */
    protected function sendOrderConfirmation()
    {
        return $this->getNotificationService()->notifyOrderConfirmation($this);
    }

    /**
     * Send payment reminder SMS/WhatsApp
     */
    protected function sendPaymentReminder($paymentUrl = null)
    {
        return $this->getNotificationService()->notifyPaymentReminder($this, $paymentUrl);
    }

    /**
     * Send event reminder (3 days)
     */
    protected function sendEventReminder3Days()
    {
        return $this->getNotificationService()->notifyEventReminder3Days($this);
    }

    /**
     * Send event reminder (1 day)
     */
    protected function sendEventReminder1Day($teamInfo = 'Tim profesional kami')
    {
        return $this->getNotificationService()->notifyEventReminder1Day($this, $teamInfo);
    }

    /**
     * Send event completed notification
     */
    protected function sendEventCompleted()
    {
        return $this->getNotificationService()->notifyEventCompleted($this);
    }

    /**
     * Format phone number
     */
    public static function formatPhone($phone): string
    {
        return SmsService::formatPhoneNumber($phone);
    }
}
