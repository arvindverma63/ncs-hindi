@forelse($messages as $msg)
    @php
        $isMe = $msg->seeker_id === Auth::id() && $msg->subject !== 'Coach Reply';
    @endphp

    <div class="d-flex mb-4 {{ $isMe ? 'justify-content-end' : 'justify-content-start' }}">
        <div class="message-wrapper {{ $isMe ? 'text-end' : '' }}" style="max-width: 70%;">
            <div class="message-box p-3 shadow-sm {{ $isMe ? 'bg-primary text-white' : 'bg-white text-dark' }}"
                style="border-radius: 15px; {{ $isMe ? 'border-bottom-right-radius: 0;' : 'border-bottom-left-radius: 0;' }}">
                <p class="mb-0 fs-14">{!! $msg->message !!}</p>
            </div>

            <small class="text-muted font-size-11 mt-1 d-block">
                {{ $msg->created_at->format('h:i A') }}
                @if ($isMe)
                    <i class="mdi mdi-check-all {{ $msg->status === 'read' ? 'text-primary' : 'text-muted' }} ms-1"></i>
                @endif
            </small>
        </div>
    </div>
@empty
    <div class="h-100 d-flex align-items-center justify-content-center flex-column">
        <div class="avatar-lg bg-soft-primary text-primary rounded-circle mb-3 d-flex align-items-center justify-content-center"
            style="width: 80px; height: 80px;">
            <iconify-icon icon="tabler:messages" class="display-5"></iconify-icon>
        </div>
        <h5 class="fw-bold">Start the Conversation</h5>
    </div>
@endforelse
