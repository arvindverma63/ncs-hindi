<x-app-layout title="Compose Message | Admin">
    <div class="content">
        <div class="container-fluid">
            <div class="py-3 d-flex align-items-center justify-content-between">
                <h4 class="fs-18 fw-bold m-0">Direct Communication</h4>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 font-size-12">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Admin</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.requests.index') }}">Messages</a></li>
                        <li class="breadcrumb-item active">Compose</li>
                    </ol>
                </nav>
            </div>

            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm overflow-hidden">
                        <div class="card-header bg-light border-bottom-0 p-3">
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm me-3">
                                    @if($targetUser->profile_image)
                                        <img src="{{ asset($targetUser->profile_image) }}" class="img-fluid rounded-circle" alt="">
                                    @else
                                        <div class="avatar-title bg-soft-primary text-primary rounded-circle fw-bold">
                                            {{ substr($targetUser->name, 0, 1) }}
                                        </div>
                                    @endif
                                </div>
                                <div>
                                    <h5 class="m-0 fs-15 fw-bold">Messaging: {{ $targetUser->name }}</h5>
                                    <p class="text-muted mb-0 font-size-12">Role: 
                                        <span class="badge bg-soft-secondary text-secondary">
                                            {{ $targetUser->user_type == 2 ? 'Coach' : 'Seeker' }}
                                        </span>
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="card-body p-4">
                            <form action="{{ route('admin.messages.store') }}" method="POST" id="adminMsgForm">
                                @csrf
                                <input type="hidden" name="receiver_id" value="{{ $targetUser->id }}">

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Subject Line</label>
                                    <input type="text" name="subject" class="form-control form-control-lg fs-14" 
                                           placeholder="e.g., Important update regarding your profile..." required>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-semibold">Your Message</label>
                                    <div id="quill-editor" style="height: 250px;"></div>
                                    <input type="hidden" name="message" id="message-content">
                                </div>

                                <div class="alert alert-info border-0 bg-soft-info d-flex align-items-center" role="alert">
                                    <iconify-icon icon="tabler:info-circle" class="fs-4 me-2"></iconify-icon>
                                    <div class="font-size-13">
                                        As an <strong>Administrator</strong>, this message bypasses the standard connection request and will appear directly in the user's inbox.
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <a href="{{ url()->previous() }}" class="text-muted text-decoration-none font-size-14">
                                        <i class="mdi mdi-arrow-left me-1"></i> Back to list
                                    </a>
                                    <button type="submit" class="btn btn-primary px-5 shadow-sm">
                                        <i class="mdi mdi-send-check me-1"></i> Send Direct Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        
        <script>
            var quill = new Quill('#quill-editor', {
                theme: 'snow',
                placeholder: 'Type your message here...',
                modules: {
                    toolbar: [
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered'}, { 'list': 'bullet' }],
                        ['link', 'clean']
                    ]
                }
            });

            document.getElementById('adminMsgForm').onsubmit = function() {
                var content = document.querySelector('input[id=message-content]');
                content.value = quill.root.innerHTML;
                
                if (quill.getText().trim().length === 0) {
                    Swal.fire('Error', 'Message content cannot be empty!', 'error');
                    return false;
                }
            };
        </script>
    @endpush
</x-app-layout>

<style>
    .btn-soft-info { background-color: rgba(0, 192, 239, 0.1); color: #00c0ef; border: none; }
    .btn-soft-info:hover { background-color: #00c0ef; color: white; }
    .bg-soft-primary { background-color: rgba(75, 73, 172, 0.1) !important; }
    .avatar-sm { height: 40px; width: 40px; }
    .avatar-title { height: 100%; width: 100%; display: flex; align-items: center; justify-content: center; }
</style>