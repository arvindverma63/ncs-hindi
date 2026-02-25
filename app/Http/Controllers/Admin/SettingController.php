<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingService;
use App\Actions\Settings\{
    UpdateGeneralSettingsAction,
    UpdatePaymentGatewayAction,
    UpdateSmsGatewayAction,
    UpdateMailConfigAction,
    UpdateSocialLinksAction,
    UpdatePageSettingsAction
};
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingService $settingService
    ) {}

    public function index(): View
    {
        return view('admin.settings.index', ['settings' => $this->settingService]);
    }

    public function update(Request $request, UpdateGeneralSettingsAction $action)
    {
        try {
            $action->handle($request);
            return back()->with('success', 'General settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function paymentGateway(): View
    {
        return view('admin.settings.payment-gateway', ['settings' => $this->settingService]);
    }

    public function updatePaymentGateway(Request $request, UpdatePaymentGatewayAction $action)
    {
        try {
            $action->handle($request);
            return back()->with('success', 'Payment Gateway updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function smsGateway(): View
    {
        return view('admin.settings.sms-gateway', ['settings' => $this->settingService]);
    }

    public function updateSmsGateway(Request $request, UpdateSmsGatewayAction $action)
    {
        try {
            $action->handle($request);
            return back()->with('success', 'SMS Gateway updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function mailConfig(): View
    {
        return view('admin.settings.mail-config', ['settings' => $this->settingService]);
    }

    public function updateMailConfig(Request $request, UpdateMailConfigAction $action)
    {
        try {
            $action->handle($request);
            return back()->with('success', 'Mail Configuration updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function socialLinks(): View
    {
        return view('admin.settings.social-links', ['settings' => $this->settingService]);
    }

    public function updateSocialLinks(Request $request, UpdateSocialLinksAction $action)
    {
        try {
            $action->handle($request);
            return back()->with('success', 'Social Links updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    public function pageSetting(): View
    {
        return view('admin.settings.page-setting', ['settings' => $this->settingService]);
    }

    public function updatePageSetting(Request $request, UpdatePageSettingsAction $action)
    {
        try {
            $action->handle($request);
            return back()->with('success', 'Page settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}