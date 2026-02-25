@forelse($messages as $msg)
    @php
        // Identify messages sent by the coach vs the seeker
        // Adjust the 'subject' check if you use different naming conventions for coach replies
        $isCoachReply =
            ($msg->coach_id === Auth::id() && $msg->subject === 'Coach Reply') || $msg->sender_id === Auth::id();
    @endphp

    <div class="d-flex mb-4 {{ $isCoachReply ? 'justify-content-end' : 'justify-content-start' }}">
        <div class="message-content {{ $isCoachReply ? 'text-end' : '' }}" style="max-width: 70%;">

            {{-- Message Bubble --}}
            <div class="p-3 shadow-sm {{ $isCoachReply ? 'bg-primary text-white' : 'bg-white text-dark' }}"
                style="border-radius: 15px; {{ $isCoachReply ? 'border-bottom-right-radius: 0;' : 'border-bottom-left-radius: 0;' }}">
                <p class="mb-0 fs-14">{!! $msg->message !!}</p>
            </div>

            {{-- Timestamp and Read Receipt --}}
            <small class="text-muted font-size-11 mt-1 d-block">
                {{ $msg->created_at->format('h:i A') }}
                @if ($isCoachReply)
                    <i class="mdi mdi-check-all {{ $msg->status === 'read' ? 'text-primary' : 'text-muted' }} ms-1"
                        title="{{ ucfirst($msg->status) }}"></i>
                @endif
            </small>
        </div>
    </div>
@empty
    <div class="h-100 d-flex align-items-center justify-content-center flex-column py-5">
        <div class="avatar-lg bg-soft-primary text-primary rounded-circle mb-3 d-flex align-items-center justify-content-center"
            style="width: 80px; height: 80px;">
            <iconify-icon icon="tabler:message-chatbot" class="display-5"></iconify-icon>
        </div>
        <h5 class="fw-bold">No Messages Yet</h5>
        <p class="text-muted px-5 text-center">Break the ice! Send a welcome message to start the conversation.</p>
    </div>
@endforelse
