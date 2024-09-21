<?php

namespace App\Services;

use App\Models\Blog;
use App\Models\Payvoice;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class PayvoiceService
{
    const DEVICE_ICON = [
        'desktop' => 'images/device/desktop.svg',
        'phone' => 'images/device/phone.svg',
        'tablet' => 'images/device/tablet.svg',
    ];

    const PLATFORM_ICON = [
        'AndroidOS' => 'images/platform/AndroidOS.svg',
        'ChromeOS' => 'images/platform/ChromeOS.svg',
        'iOS' => 'images/platform/iOS.svg',
        'Linux' => 'images/platform/Linux.svg',
        'Ubuntu' => 'images/platform/Ubuntu.svg',
        'Windows' => 'images/platform/Windows.svg',
    ];

    const BROWSER_ICON = [
        'Chrome' => 'images/browser/Chrome.svg',
        'Edge' => 'images/browser/Edge.svg',
        'Firefox' => 'images/browser/Firefox.svg',
        'IE' => 'images/browser/IE.svg',
    ];

    public function getLatestBlogPayvoicesQuery(Blog $blog)
    {
        return $blog->payvoices()->orderDefault();
    }

    public function store(Blog $blog, Request $request)
    {
        $agent = new Agent([], request()->userAgent());

        Payvoice::create([
            'ip' => $request->ip() ?: null,
            'method' => $request->method() ?: null,
            'controller' => ($request->route() and $request->route()->action['controller'] ? $request->route()->action['controller'] : null),
            'useragent_device' => $agent->deviceType() ?: null,
            'useragent_browser' => $agent->browser() ?: null,
            'useragent_platform' => $agent->platform() ?: null,
            'useragent_robot' => $agent->robot() ?: null,
            'useragent' => $request->userAgent() ?: null,
            'blog_id' => $blog->id,
        ]);
    }

    public function getDeviceIcon(Payvoice $payvoice)
    {
        if (array_key_exists($payvoice->useragent_device, static::DEVICE_ICON)) {
            return asset(static::DEVICE_ICON[$payvoice->useragent_device]);
        }

        return null;
    }

    public function getPlatformIcon(Payvoice $payvoice)
    {
        if (array_key_exists($payvoice->useragent_platform, static::PLATFORM_ICON)) {
            return asset(static::PLATFORM_ICON[$payvoice->useragent_platform]);
        }

        return null;
    }

    public function getBrowserIcon(Payvoice $payvoice)
    {
        if (array_key_exists($payvoice->useragent_browser, static::BROWSER_ICON)) {
            return asset(static::BROWSER_ICON[$payvoice->useragent_browser]);
        }

        return null;
    }
}
