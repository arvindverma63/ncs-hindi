<x-guest-layout title="Login | Business Coach">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100 py-5">
            <div class="col-md-8 col-lg-6 col-xl-5">

                <div class="card shadow border-0 rounded-4">
                    <div class="card-body p-5">

                        <div class="text-center mb-4">

                            <h3 class="fw-bold text-dark">Welcome Back</h3>
                            <p class="text-muted">Please sign in to continue to Business Coach Admin.</p>
                        </div>

                        <form method="POST" action="{{ route('super.login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email"
                                    class="form-control form-control-lg @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" placeholder="Enter your email" required
                                    autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input id="password" type="password"
                                    class="form-control form-control-lg @error('password') is-invalid @enderror"
                                    name="password" placeholder="Enter your password" required
                                    autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me"
                                        {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="remember_me">
                                        {{ __('Remember me') }}
                                    </label>
                                </div>

                                {{-- @if (Route::has('password.request'))
                                    <a class="text-decoration-none small" href="{{ route('password.request') }}">
                                        {{ __('Forgot password?') }}
                                    </a>
                                @endif --}}
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    {{ __('Log In') }}
                                </button>
                            </div>

                        </form>

                        <div class="text-center mt-4">
                            <p class="text-muted mb-0">Don't have an account?
                                <a href="{{ route('register') }}" class="fw-bold text-decoration-none">Sign Up</a>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>
