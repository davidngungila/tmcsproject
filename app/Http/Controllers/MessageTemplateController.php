<?php

namespace App\Http\Controllers;

use App\Models\MessageTemplate;
use Illuminate\Http\Request;
use App\Services\MessagingService;
use Illuminate\Support\Facades\Mail;

class MessageTemplateController extends Controller
{
    protected $messagingService;

    public function __construct(MessagingService $messagingService)
    {
        $this->messagingService = $messagingService;
    }

    public function test(Request $request)
    {
        $request->validate([
            'test_recipient' => 'required|string',
            'type' => 'required|string|in:SMS,Email,WhatsApp',
            'content' => 'required|string',
            'subject' => 'nullable|string',
        ]);

        $recipient = $request->test_recipient;
        $content = $request->content;
        $subject = $request->subject ?? 'TMCS Test Message';

        try {
            if ($request->type === 'Email') {
                Mail::raw($content, function ($message) use ($recipient, $subject) {
                    $message->to($recipient)
                            ->subject($subject);
                });
                return back()->with('success', 'Test Email sent successfully to ' . $recipient);
            } elseif ($request->type === 'SMS') {
                $response = $this->messagingService->sendSms($recipient, $content);
                if ($response['status'] === 'success') {
                    return back()->with('success', 'Test SMS sent successfully to ' . $recipient);
                }
                return back()->with('error', 'SMS Error: ' . ($response['message'] ?? 'Failed to send'));
            } elseif ($request->type === 'WhatsApp') {
                $response = $this->messagingService->sendWhatsApp($recipient, $content);
                if ($response['status'] === 'success') {
                    return back()->with('success', 'Test WhatsApp sent successfully to ' . $recipient);
                }
                return back()->with('error', 'WhatsApp Error: ' . ($response['message'] ?? 'Failed to send'));
            }
        } catch (\Exception $e) {
            return back()->with('error', 'System Error: ' . $e->getMessage());
        }

        return back()->with('error', 'Invalid message type selected.');
    }

    public function index()
    {
        $templates = MessageTemplate::latest()->paginate(10);
        return view('message_templates.index', compact('templates'));
    }

    public function create()
    {
        return view('message_templates.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:SMS,Email,WhatsApp',
            'is_active' => 'boolean',
        ]);

        MessageTemplate::create($validated);

        return redirect()->route('message-templates.index')->with('success', 'Template created successfully');
    }

    public function show(MessageTemplate $messageTemplate)
    {
        return view('message_templates.show', compact('messageTemplate'));
    }

    public function edit(MessageTemplate $messageTemplate)
    {
        return view('message_templates.edit', compact('messageTemplate'));
    }

    public function update(Request $request, MessageTemplate $messageTemplate)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'subject' => 'nullable|string|max:255',
            'content' => 'required|string',
            'type' => 'required|string|in:SMS,Email,WhatsApp',
            'is_active' => 'boolean',
        ]);

        $messageTemplate->update($validated);

        return redirect()->route('message-templates.index')->with('success', 'Template updated successfully');
    }

    public function destroy(MessageTemplate $messageTemplate)
    {
        $messageTemplate->delete();
        return redirect()->route('message-templates.index')->with('success', 'Template deleted successfully');
    }
}
