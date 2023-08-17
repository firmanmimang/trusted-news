@guest
  @if(!request()->has('page') && !request()->has('q') && !request()->has('s'))
    <script src="https://accounts.google.com/gsi/client" async defer></script>
    <div id="g_id_onload"
        data-client_id="{{env('GOOGLE_CLIENT_ID')}}"
        data-login_uri="{{route('login.google.one-tap')}}"
        data-_token="{{csrf_token()}}" 
        data-method="post"
        data-ux_mode="redirect"
        data-auto_prompt="true">
    </div>
  @endif
@endguest