  <!-- Add Stores into Branche -->
  <div class="table-responsive">
  <table id="tbl_stores" class="table table-bordered table-striped m-0">
   <thead>
     <tr>
       <th>
        <select class="form-control" id="select_branch">
            <option value=""><?php echo esc_attr('Show all','asl_locator') ?></option>
            <option value="assigned"><?php echo esc_attr('Assigned','asl_locator') ?></option>
            <option value="unassigned"><?php echo esc_attr('Unassigned','asl_locator') ?></option>
        </select>
       </th>
       <th><input type="text" class="form-control" data-id="id"  placeholder="<?php echo esc_attr('Search ID','asl_locator') ?>"  /></th>
       <th><input type="text" class="form-control" data-id="title"  placeholder="<?php echo esc_attr('Search Title','asl_locator') ?>"  /></th>
       <th><input type="text" class="form-control" data-id="state"  placeholder="<?php echo esc_attr('Search State','asl_locator') ?>"  /></th>
       <th><input type="text" class="form-control" data-id="city"  placeholder="<?php echo esc_attr('Search City','asl_locator') ?>"  /></th>
       <th><input type="text" class="form-control" data-id="postal_code"  placeholder="<?php echo esc_attr('Search Zip','asl_locator') ?>"  /></th>
     </tr>
     <tr>
       <th><?php echo esc_attr('Relation','asl_locator') ?></th>
       <th><?php echo esc_attr('Store ID','asl_locator') ?></th>
       <th><?php echo esc_attr('Title','asl_locator') ?></th>
       <th><?php echo esc_attr('State','asl_locator') ?></th>
       <th><?php echo esc_attr('City','asl_locator') ?></th>
       <th><?php echo esc_attr('Postal Code','asl_locator') ?></th> 
     </tr>
   </thead>
   <tbody>
   </tbody>
  </table>
  </div>
<!-- End Add Stores into Branche -->