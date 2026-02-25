<x-guest-layout title="Verify Email | Fitx">
    <div class="account-page">
        <div class="container-fluid p-0">
            <div class="row align-items-center justify-content-center g-0 px-3 py-3 vh-100">
                <div class="col-xxl-6">
                    <div class="row">
                        <div class="col-xl-6 col-md-6 mx-auto">
                            <div>
                                <div class="mb-3 p-0 text-center">
                                    <div class="auth-brand">
                                        <a href="{{ url('/') }}" class="logo logo-light">
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/fitx-logo.png') }}" alt="Fitx" height="24">
                                            </span>
                                        </a>
                                        <a href="{{ url('/') }}" class="logo logo-dark">
                                            <span class="logo-lg">
                                                <img src="{{ asset('assets/images/fitx-logo.png') }}" alt="Fitx" height="24">
                                            </span>
                                        </a>
                                    </div>
                                </div>

                                <div class="auth-title-section mb-3 text-center"> 
                                    <h4 class="text-dark fw-semibold mb-0">Verify Your Email</h4>
                                    <p class="text-muted fs-14 mb-0 mt-2">
                                        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
                                    </p>
                                </div>
                            </div>

                            <div class="card">
                                <div class="card-body">
                                    <div class="mb-0 p-0 p-lg-3">
                                        <div class="mb-0 border-0 p-md-3 p-lg-0">
                                            <div class="pt-0">

                                                @if (session('status') == 'verification-link-sent')
                                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                                    </div>
                                                @endif
                                                
                                                <div class="d-grid gap-3">
                                                    <form method="POST" action="{{ route('verification.send') }}">
                                                        @csrf
                                                        <div class="d-grid">
                                                            <button class="btn btn-primary fw-semibold" type="submit"> 
                                                                {{ __('Resend Verification Email') }} 
                                                            </button>
                                                        </div>
                                                    </form>

                                                    <form method="POST" action="{{ route('logout') }}" class="text-center">
                                                        @csrf
                                                        <button type="submit" class="btn btn-link text-muted fw-medium text-decoration-none">
                                                            {{ __('Log Out') }}
                                                        </button>
                                                    </form>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>