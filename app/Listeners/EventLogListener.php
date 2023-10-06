<?php

namespace App\Listeners;

use App\Events\PasswordResetFailed;
use App\Events\PasswordResetRequested;
use App\Events\PasswordUpdateFailed;
use App\Events\PasswordUpdated;
use App\Events\ServiceScheduleFailed;
use App\Events\ServiceScheduled;
use App\Models\Fabric\EventLog;
use Illuminate\Auth\Events\Failed as LoginFailed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Log;

class EventLogListener
{
    /**
     * Handle the login event.
     *
     * @return void
     */
    public function handleLogin(Login $event): EventLog
    {
        return EventLog::make()
            ->setUser($event->user)
            ->setArea('authentication')
            ->setAction('login');
    }

    /**
     * Handle the login failed event.
     *
     * @param \Illuminate\Auth\Events\Failed $event
     * @return void
     */
    public function handleLoginFailed(LoginFailed $event): EventLog
    {
        $log = EventLog::make()
            ->setFailed()
            ->setAction('login')
            ->setArea('authentication');

        $event->user
            ? $log->setUser($event->user)
            : $log->setValue($event->credentials['email']);

        return $log;
    }

    /**
     * Handle the logout event.
     *
     * @return void
     */
    public function handleLogout(Logout $event): EventLog
    {
        return EventLog::make()
            ->setUser($event->user)
            ->setArea('authentication')
            ->setAction('logout');
    }

    /**
     * Handle the password reset requested event.
     *
     * @param \App\Events\PasswordUpdated $event
     * @return void
     */
    public function handlePasswordUpdated(PasswordUpdated $event): EventLog
    {
        return EventLog::make()
            ->setUser($event->user)
            ->setArea('security')
            ->setAction('password_updated');
    }

    /**
     * Handle the password reset requested event.
     *
     * @param \App\Events\PasswordUpdated $event
     * @return void
     */
    public function handlePasswordUpdateFailed(PasswordUpdateFailed $event): EventLog
    {
        return EventLog::make()
            ->setUser($event->user)
            ->setArea('security')
            ->setAction('password_updated')
            ->setFailed();
    }

    /**
     * Handle the password reset requested event.
     *
     * @param \App\Events\PasswordResetRequested $event
     * @return void
     */
    public function handlePasswordResetRequested(PasswordResetRequested $event): EventLog
    {
        return EventLog::make()
            ->setUser($event->user)
            ->setArea('security')
            ->setAction('password_reset_requested')
            ->setValue($event->user->email);
    }

    /**
     * Handle the password reset event.
     *
     * @param \Illuminate\Auth\Events\PasswordReset $event
     * @return void
     */
    public function handlePasswordReset(PasswordReset $event): EventLog
    {
        return EventLog::make()
            ->setUser($event->user)
            ->setArea('security')
            ->setAction('password_reset')
            ->setValue($event->user->email);
    }

    /**
     * Handle the password reset failed event.
     *
     * @param \Illuminate\Auth\Events\PasswordResetFailed $event
     * @return void
     */
    public function handlePasswordResetFailed(PasswordResetFailed $event): EventLog
    {
        return EventLog::make()
            ->setUser($event->user)
            ->setArea('security')
            ->setAction('password_reset')
            ->setValue($event->token)
            ->setFailed();
    }

    /**
     * Handle the service run event.
     *
     * @param \App\Events\ServiceScheduled $event
     * @return void
     */
    public function handleServiceScheduled(ServiceScheduled $event): EventLog
    {
        return EventLog::make()
            ->setUser(auth()->user())
            ->setArea('service')
            ->setAction('service_scheduled_manually')
            ->setValue("{$event->service['id']} / {$event->service['description']}");
    }

    /**
     * Handle the service run event.
     *
     * @param \App\Events\ServiceScheduleFailed $event
     * @return void
     */
    public function handleServiceScheduleFailed(ServiceScheduleFailed $event): EventLog
    {
        return EventLog::make()
            ->setUser(auth()->user())
            ->setArea('service')
            ->setAction('service_scheduled_manually')
            ->setValue("{$event->service['id']} / {$event->service['description']}")
            ->setFailed();
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event): void
    {
        try {
            $log = $this->createLog($event);

            $log->save();
        } catch (\Throwable $th) {
            Log::emergency('An error occurred while logging an event', [
                'event' => get_class($event),
                'exception' => $th->getMessage()
            ]);
        }
    }

    /**
     * Make a new event log instance.
     *
     * @param  object  $event
     * @return EventLog|null
     */
    public function createLog($event)
    {
        switch (true) {
            case $event instanceof Login:
                return $this->handleLogin($event);
            case $event instanceof LoginFailed:
                return $this->handleLoginFailed($event);
            case $event instanceof Logout:
                return $this->handleLogout($event);
            case $event instanceof PasswordUpdated:
                return $this->handlePasswordUpdated($event);
            case $event instanceof PasswordUpdateFailed:
                return $this->handlePasswordUpdateFailed($event);
            case $event instanceof PasswordResetRequested:
                return $this->handlePasswordResetRequested($event);
            case $event instanceof PasswordReset:
                return $this->handlePasswordReset($event);
            case $event instanceof PasswordResetFailed:
                return $this->handlePasswordResetFailed($event);
            case $event instanceof ServiceScheduled:
                return $this->handleServiceScheduled($event);
            case $event instanceof ServiceScheduleFailed:
                return $this->handleServiceScheduleFailed($event);
        }
    }
}
