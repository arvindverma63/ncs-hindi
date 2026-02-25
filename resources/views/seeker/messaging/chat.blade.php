<x-seeker-layout title="Chat with {{ $coach->name }} | BestBusinessCoach">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-xl-9">
                <div class="card border-0 shadow-sm mb-0"
                    style="height: calc(100vh - 150px); display: flex; flex-direction: column; overflow: hidden;">

                    <div class="card-header bg-white border-bottom p-3">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="position-relative">
                                    <img src="{{ $coach->profile_image && file_exists(public_path($coach->profile_image)) ? asset($coach->profile_image) : asset('assets/images/users/user.avif') }}"
                                        class="rounded-circle border"
                                        style="width: 48px; height: 48px; object-fit: cover;">
                                    <span class="position-absolute bottom-0 end-0 bg-success border border-2 border-white rounded-circle"
                                        style="width: 12px; height: 12px;"></span>
                                </div>
                                <div class="ms-3">
                                    <h5 class="m-0 fs-16 fw-bold text-dark">{{ $coach->name }}</h5>
                                    <p class="text-muted mb-0 font-size-12">Verified Business Coach</p>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-light btn-sm rounded-circle" data-bs-toggle="dropdown">
                                    <i class="mdi mdi-dots-vertical"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="{{ route('seeker.coaches.index') }}">View Profile</a></li>
                                    {{-- <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item text-danger" href="#">Report User</a></li> --}}
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div id="chat-conversation" class="card-body bg-light-subtle p-4"
                        style="flex: 1 1 auto; overflow-y: auto;">
                        <div id="message-container">
                            @include('seeker.messaging._messages')
                        </div>
                    </div>

                    <div class="card-footer bg-white border-top p-3">
                        <form action="{{ route('seeker.messaging.send', $coach->id) }}" method="POST" id="chat-form">
                            @csrf
                            <div class="row g-2">
                                <div class="col">
                                    <input type="text" name="message" id="chat-input"
                                        class="form-control form-control-lg border-0 bg-light fs-14"
                                        placeholder="Type your message here..." required autocomplete="off">
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
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script>
            $(document).ready(function() {
                const viewport = document.getElementById('chat-conversation');
                const fetchUrl = "{{ route('seeker.messaging.fetch', $coach->id) }}"; // Create this route

                function scrollToBottom() {
                    viewport.scrollTop = viewport.scrollHeight;
                }

                scrollToBottom();

                // Polling for new messages
                function refreshChat() {
                    $.ajax({
                        url: fetchUrl,
                        type: 'GET',
                        success: function(html) {
                            const isAtBottom = viewport.scrollTop + viewport.clientHeight >= viewport.scrollHeight - 50;
                            $('#message-container').html(html);
                            if (isAtBottom) scrollToBottom();
                        }
                    });
                }

                setInterval(refreshChat, 3000);

                // AJAX Send
                $('#chat-form').on('submit', function(e) {
                    e.preventDefault();
                    const form = $(this);
                    const input = $('#chat-input');
                    const btn = $('#send-btn');

                    if (input.val().trim() === '') return false;

                    $.ajax({
                        url: form.attr('action'),
                        type: 'POST',
                        data: form.serialize(),
                        beforeSend: function() { btn.prop('disabled', true); },
                        success: function() {
                            input.val('');
                            refreshChat();
                            btn.prop('disabled', false);
                            scrollToBottom();
                        }
                    });
                });
            });
        </script>
    @endpush
</x-seeker-layout>