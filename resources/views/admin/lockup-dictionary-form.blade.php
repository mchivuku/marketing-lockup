<form data-api="{{URL::to("admin/lockup-dic")}}" class="filter hide-labels"
      action="{{URL::to("admin/lockup-dic")}}" method="GET">
    <div class="grid thirds">
        <div class="form-item">
            <div class="form-item-label"><label for="campus">Campus</label></div>
            <div class="form-item-input">
                   <select id="campus" class="campus" name="campus">
                    <option selected="" value="iu">Indiana University (IU like campus)</option>
                    <option value="iupui">IUPUI (IUPUI like campus)</option>
                   </select>
            </div>
        </div>
        <div class="form-item">
            <div class="form-item-label"><label for="orientation">Orientation</label></div>
            <div class="form-item-input"><select id="orientation" class="orientation" name="orientation">
                    <option selected="" value="0">All orientations</option>
                    <option value="horizontal">Horizontal</option>
                    <option value="vertical">Vertical</option>
                </select></div>
        </div>
        <div class="form-item">
            <div class="form-item-label"><label for="topic">School Type</label></div>
            <div class="form-item-input"><select id="named" class="named" name="named">
                    <option selected="" value="0">All Types</option>
                    <option value="named">Named school</option>
                    <option value="nonnamed">Non named school</option>
                </select></div>
        </div>
    </div>
</form>