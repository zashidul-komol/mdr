@extends('layouts.error', ['className' => 'error-503'])

@section('content')
<div class="row animated bounce">
    <div class="col-sm-8 col-sm-offset-2">
        <div class="panel mt-xlg">
            <div class="panel-content text-center">
                <h1 class="error-number">503</h1>
                <h2 class="error-name">Maintenance Mode</h2>

                <p class="error-text">
                    We’re currently performing scheduled maintenance.
                    <br/>
                    Please check back in a little while.
                </p>

                <div class="row mt-xlg">
                    <div class="col-sm-6 col-sm-offset-3">
                        <button class="btn btn-sm btn-darker-2 btn-block" onclick="location.reload();">
                            Retry
                        </button>

                        <a href="{{ url('/') }}" class="btn btn-sm btn-primary btn-block">
                            Go to Homepage
                        </a>

                        <a href="pages_faq" class="btn btn-sm btn-lighter-2 btn-block mb-xlg">
                            FAQ
                        </a>
                    </div>
                </div>

                <small class="text-muted">
                    Thank you for your patience 🙏
                </small>
            </div>
        </div>
    </div>
</div>
@endsection
