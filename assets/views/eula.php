<div class="container">
  <div class="row">
    <div class="span8 offset2">
      <form method="POST">
        <fieldset>
          <legend>Please agree</legend>
          <label>End User Agreement:</label>
          <input type="textarea" name="eulatext" value="<?=$eula->EULA_TXT?>">
          <input type="hidden" name="type" value="<?=$eula->TYPE_CODE?>">
          <br/>
          <br/>
          <button type="submit" class="btn">Agree</button>
        </fieldset>
      </form>
      <!-- Link to facebook login 
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
        <a href="/facebook/popup" onclick="return popup(this, 'fblogin')">Login via Facebook Popup</a>
      </div>!-->
    </div>
  </div>
</div> 