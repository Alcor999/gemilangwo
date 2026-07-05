<?php

namespace App\Console\Commands;

use App\Models\CalendarEvent;
use App\Models\Order;
use Illuminate\Console\Command;

class SyncCalendarEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calendar:sync 
                            {--dry-run : Run without making changes}
                            {--confirm-all : Auto-confirm events for confirmed orders}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync calendar events with confirmed orders';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $confirmAll = $this->option('confirm-all');

        $this->info('🔄 Syncing Calendar Events with Orders...');
        $this->newLine();

        // Step 1: Create missing calendar events for confirmed orders
        $this->info('📅 Step 1: Creating missing calendar events...');
        $ordersWithoutEvents = Order::where('status', 'confirmed')
            ->whereDoesntHave('calendarEvent')
            ->get();

        if ($ordersWithoutEvents->count() > 0) {
            $this->table(
                ['Order ID', 'Order Number', 'Package', 'Event Date'],
                $ordersWithoutEvents->map(function ($order) {
                    return [
                        $order->id,
                        $order->order_number,
                        $order->package->name ?? 'N/A',
                        $order->event_date ? $order->event_date->format('d M Y') : 'N/A',
                    ];
                })
            );

            if (!$isDryRun) {
                $created = 0;
                foreach ($ordersWithoutEvents as $order) {
                    CalendarEvent::createFromOrder($order);
                    $created++;
                }
                $this->info("✅ Created {$created} calendar events");
            } else {
                $this->warn("🔍 DRY RUN: Would create {$ordersWithoutEvents->count()} calendar events");
            }
        } else {
            $this->info('✅ All confirmed orders have calendar events');
        }

        $this->newLine();

        // Step 2: Confirm calendar events for confirmed orders
        if ($confirmAll) {
            $this->info('✅ Step 2: Confirming calendar events...');
            $unconfirmedEvents = CalendarEvent::where('is_confirmed', false)
                ->whereHas('order', function ($q) {
                    $q->whereIn('status', ['confirmed', 'in_progress', 'completed']);
                })
                ->get();

            if ($unconfirmedEvents->count() > 0) {
                $this->table(
                    ['Event ID', 'Order', 'Package', 'Event Date', 'Order Status'],
                    $unconfirmedEvents->map(function ($event) {
                        return [
                            $event->id,
                            $event->order->order_number,
                            $event->package->name ?? 'N/A',
                            $event->event_date->format('d M Y'),
                            $event->order->status,
                        ];
                    })
                );

                if (!$isDryRun) {
                    $confirmed = 0;
                    foreach ($unconfirmedEvents as $event) {
                        $event->update([
                            'is_confirmed' => true,
                            'confirmed_at' => now(),
                        ]);
                        $confirmed++;
                    }
                    $this->info("✅ Confirmed {$confirmed} calendar events");
                } else {
                    $this->warn("🔍 DRY RUN: Would confirm {$unconfirmedEvents->count()} calendar events");
                }
            } else {
                $this->info('✅ All calendar events for confirmed orders are already confirmed');
            }
        }

        $this->newLine();

        // Step 3: Complete calendar events for past dates
        $this->info('📅 Step 3: Auto-completing past events...');
        $pastEvents = CalendarEvent::where('status', '!=', 'completed')
            ->where('event_date', '<', now()->toDateString())
            ->get();

        if ($pastEvents->count() > 0) {
            $this->table(
                ['Event ID', 'Order ID', 'Event Date', 'Current Status'],
                $pastEvents->map(function ($event) {
                    return [
                        $event->id,
                        $event->order_id,
                        $event->event_date ? $event->event_date->format('d M Y') : 'N/A',
                        $event->status,
                    ];
                })
            );

            if (!$isDryRun) {
                $completed = 0;
                foreach ($pastEvents as $event) {
                    $event->update(['status' => 'completed']);
                    if ($event->order && $event->order->status !== 'completed') {
                        $event->order->update(['status' => 'completed']);
                    }
                    $completed++;
                }
                $this->info("✅ Auto-completed {$completed} past events");
            } else {
                $this->warn("🔍 DRY RUN: Would auto-complete {$pastEvents->count()} past events");
            }
        } else {
            $this->info('✅ All past events are already marked as completed');
        }

        $this->newLine();
        $this->info('✨ Sync completed!');

        return Command::SUCCESS;
    }
}
