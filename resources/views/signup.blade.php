<!-- Temporary sign up template -->
<form action="/api/register" method="POST">
    {!! csrf_field() !!}
    <div>
        <label>
            First name:
            <input type="text" name="first_name">
        </label>
    </div>
    <div>
        <label>
            Last name:
            <input type="text" name="last_name">
        </label>
    </div>
    <div>
        <label>
            Mobile number:
            <input type="text" name="mobile_number">
        </label>
    </div>
    <div>
        <label>
            Password:
            <input type="password" name="password">
        </label>
    </div>
    <input type="submit" value="signup">
</form>