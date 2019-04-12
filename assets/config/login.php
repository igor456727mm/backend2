<form method="POST">
    <fieldset>
        <legend>Fairy login</legend>
        <label>Username</label>
        <input type="text" name="username" placeholder="Your name...">
        <label>Password</label>
        <input type="password" name="password">
        <span class="help-block">Enter the fairy realm.</span>
        <button type="submit" class="btn">Submit</button>
    </fieldset>
</form>
 
<!-- Link to facebook login -->
<div>
    <a href="/facebook">Login via Facebook</a>
</div>
 
<script>
    //A very basic way to open a popup
    function popup(link, windowname) {
        window.open(link.href, windowname, 'width=400,height=200,scrollbars=yes');
        return false;
    }
</script>
<div>
    <a href="/facebook/popup" onclick="return popup(this,'fblogin')">Login via Facebook Popup</a>
</div>