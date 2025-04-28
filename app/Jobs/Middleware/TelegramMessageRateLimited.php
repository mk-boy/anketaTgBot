<?php

namespace App\Jobs\Middleware;

use Illuminate\Contracts\Queue\Job;
use Illuminate\Support\Facades\Redis as RedisFacade;
use Telegram\Bot\Objects\Update as UpdateObject;
use Illuminate\Support\Facades\Log;

class TelegramMessageRateLimited
{
    /**
     * Process the queued job.
     *
     * @param  mixed  $job
     * @param  callable  $next
     * @return mixed
     */
    public function handle($job, $next)
    {
        return $next($job);
        $key = $job->getChatId();

        RedisFacade::throttle('total')->allow(30)->every(1)->then(function () use($key, $job, $next) {
           RedisFacade::throttle($key)->allow(1)->every(1)->then(function () use($job, $next) {
               $next($job);
           }, function () use ($job){
               // Could not obtain lock; this job will be re-queued
               $job->release(1);
           });
        }, function () use ($job) {
           // Could not obtain lock; this job will be re-queued
           $job->release(1);
        });

        /*
        Redis::throttle('key')
                ->block(0)->allow(1)->every(10)
                ->then(function () use ($job, $next) {
                    // Lock obtained...

                    $next($job);
                }, function () use ($job) {
                    // Could not obtain lock...

                    $job->release(1);
                });
                */
    }
}
