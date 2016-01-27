
 <div class="toggleElements">


     <label for="primary">Default Primary <br/>
         <input id="primary" name="p"  class="default-primary" type="text" required
                value="{{$campus}}" readonly>
     </label>


     <label for="secondary">Secondary<br/><span
                 class="help-text"i d="replace-secondary">(ex. School of Business, School of Law)</span>
         <input id="secondary" name="s"   type="text" placeholder='SECONDARY' required
                value="{{$secondaryText}}">
     </label>


     <label for="tertiary">Tertiary<br/><span class="help-text">(ex. Campus, office or unit)</span>
         <input id="tertiary" name="t"  type="text" placeholder='Tertiary'  value="{{$tertiaryText}}">
     </label>


 </div>

