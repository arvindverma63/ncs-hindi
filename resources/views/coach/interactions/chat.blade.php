<x-coach-layout title="Chat with {{ $seeker->name }} | BestBusinessCoach">

    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-10">
                <div class="card border-0 shadow-sm mb-0"
                    style="height: calc(100vh - 160px); display: flex; flex-direction: column; overflow: hidden;">

                    <div class="card-header bg-white border-bottom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    {{-- Profile image logic --}}
                                    <img src="{{ $seeker->profile_image && file_exists(public_path($seeker->profile_image)) ? asset($seeker->profile_image) : asset('assets/images/users/user.avif') }}"
                                        class="rounded-circle border"
                                        style="width: 48px; height: 48px; object-fit: cover;">
                                    <span
                                        class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                        style="width: 12px; height: 12px;"></span>
                                </div>
                                <div class="ms-3">
                                    <h5 class="m-0 fs-16 fw-bold text-dark">{{ $seeker->name }}</h5>
                                    <p class="text-muted mb-0 font-size-12">Connected Seeker</p>
                                </div>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('coach.interactions.index') }}" class="btn btn-light btn-sm">
                                    <i class="mdi mdi-arrow-left me-1"></i> Back to Inbox
                                </a>
                            </div>
                        </div>
                    </div>

                    <div id="coach-chat-viewport" class="card-body bg-light-subtle p-4"
                        style="flex: 1 1 auto; overflow-y: auto;">
                        {{-- This container is targeted by jQuery for dynamic loading --}}
                        <div id="message-container">
                            @include('coach.interactions._messages')
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top p-3">
                        <form action="{{ route('coach.interactions.store') }}" method="POST" id="coach-chat-form">
                            @csrf
                            <input type="hidden" name="seeker_id" value="{{ $seeker->id }}">
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="message" id="chat-input"
                                        class="form-control form-control-lg border-0 bg-light fs-14"
                                        placeholder="Type your reply..." required autocomplete="off">
                                </div>
                                <div class="col-auto">
                                    <button type="submit" id="send-btn" class="btn btn-primary btn-lg px-4">
                                        <i class="mdi mdi-send"></i>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        {{-- Ensure jQuery is loaded before this script --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                const viewport = document.getElementById('coach-chat-viewport');
                const fetchUrl = "{{ route('coach.interactions.fetch', $seeker->id) }}";

                // Function to scroll to the bottom of the chat
                function scrollToBottom() {
                    viewport.scrollTop = viewport.scrollHeight;
                }

                // Initial scroll to bottom on page load
                scrollToBottom();

                // Dynamic Polling: Fetch new messages every 3 seconds
                function refreshChat() {
                    $.ajax({
                        url: fetchUrl,
                        type: 'GET',
                        success: function(html) {
                            // Check if the user is currently at the bottom before updating
                            const isAtBottom = viewport.scrollTop + viewport.clientHeight >= viewport
                                .scrollHeight - 50;

                            $('#message-container').html(html);

                            if (isAtBottom) {
                                scrollToBottom();
                            }
                        }
                    });
                }

                setInterval(refreshChat, 3000);

                // AJAX Form Submission: Prevent page refresh on send
                $('#coach-chat-form').on('submit', function(e) {
                    e.preventDefault();

                    const form = $(this);
                    const btn = $('#send-btn');
                    const input = $('#chat-input');

                    if (input.val().trim() === '') return false;

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        beforeSend: function() {
                            btn.prop('disabled', true);
                        },
                        success: function() {
                            input.val(''); // Clear the input field
                            refreshChat(); // Immediately refresh chat to show new message
                            btn.prop('disabled', false);
                            scrollToBottom();
                        },
                        error: function() {
                            btn.prop('disabled', false);
                            alert('Message failed to send. Please try again.');
                        }
                    });
                });
            });
        </script>
    @endpush
</x-coach-layout>

<style>
    .bg-light-subtle {
        background-color: #f9fbfd !important;
    }

    /* Scrollbar Styling for Chat */
    #coach-chat-viewport::-webkit-scrollbar {
        width: 5px;
    }

    #coach-chat-viewport::-webkit-scrollbar-thumb {
        background: #cbd5e0;
        border-radius: 10px;
    }

    /* Prevent the dashboard sidebar from causing double scrollbars */
    body {
        overflow: hidden;
    }
</style>
