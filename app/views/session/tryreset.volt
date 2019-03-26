

{{ content() }}

<div class="row">

    <div class="col-md-6">
        <div class="page-header">
            <h2>Reset Password</h2>
        </div>
        {{ form('session/reset', 'role': 'form') }}
            <fieldset>
                <div class="form-group">
                    <label for="email">Account Email</label>
                    <div class="controls">
                        {{ text_field('email', 'class': "form-control") }}
                    </div>
                </div>
                <div class="form-group">
                    {{ submit_button('Reset', 'class': 'btn btn-default btn-large') }}
                </div>
            </fieldset>
        </form>
        <a href="/session/index">Back to Login</a>
    </div>

    <div class="col-md-6">

        <div class="page-header">
            <h2>Don't have an account yet?</h2>
        </div>

        <p>Create an account to be able to use the platform best:</p>
        <ul>
            <li>Create, track and manage offers</li>
            <li>Get compensated when you give away meal credits</li>
            <li>Stay informed about Brown Bytes changes and updates</li>
        </ul>

        <div class="clearfix center">
            {{ link_to('register', 'Sign Up', 'class': 'btn btn-primary btn-large btn-success') }}
        </div>
    </div>

</div>
