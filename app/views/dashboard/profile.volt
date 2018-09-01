
{{ content() }}

<div class="profile left">
    
    <br />
    <p>
    Since all of the information stored in your profile is verified, we do not allow any users to change their personal information. See our <a href="/dashboard/privacy">privacy section</a> for more about the kinds of information we collect and who has access to that information. 
    </p>
    {{ form('dashboard/profile', 'id': 'profileForm', 'onbeforesubmit': 'return false') }}
        <br />
        <div class="clearfix">
            <label for="name">Your Full Name:</label>
            <div class="input">
                {{ text_field("name", "size": "30", "class": "span6") }}
                <div class="alert" id="name_alert">
                    <strong>Warning!</strong> Please enter your full name
                </div>
            </div>
        </div>
        <br/> 
        <div class="clearfix">
            <input type="button" value="Update" class="btn btn-primary btn-large btn-info" onclick="Profile.validate()">
            &nbsp;
            {{ link_to('dashboard/index', 'Cancel') }}
        </div>
    </form>
</div>