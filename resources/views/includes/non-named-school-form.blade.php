
<label for="secondary">Secondary (required) <br/><span
            class="help-text">(ex. School of, Department of)</span>
    <input id="secondary" name="s" required   type="text" placeholder='SECONDARY'  maxlength="51"   maxLen="50"
           value="{{$secondaryText}}">
</label>

<label for="primary">Primary (required) <br/><span class="help-text">(ex. Medicine, Psychology)</span>
    <input id="primary" name="p" placeholder='PRIMARY'  type="text" required  maxlength="51"   maxLen="50"
           value="{{$primaryText}}">
</label>

<label for="tertiary">Tertiary<br/><span class="help-text">(ex. Bloomington,Indianapolis)</span>
    <input id="tertiary" name="t"  type="text" placeholder='Tertiary'  maxlength="51"
           maxLen="50" value="{{$tertiaryText}}">
</label>



