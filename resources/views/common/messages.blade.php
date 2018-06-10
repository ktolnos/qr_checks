@if (session()->has('messages') and count(session()->get('messages')) > 0)
        <!-- Form Error List -->
<div class="alert alert-info">
<br>
    <ul>
        @foreach (session()->get('messages') as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
</div>
<?php session()->forget('messages');?>
@endif