<p>Hi,</p>

<p>
    {{ $invitation->inviter->name }} has invited you to join
    <strong>{{ $invitation->company->name }}</strong> as {{ $invitation->role }}.
</p>

<p>Click the link below to set your password and get started:</p>

<p><a href="{{ $acceptUrl }}">{{ $acceptUrl }}</a></p>
