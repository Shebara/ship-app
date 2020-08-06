<form id="ship" class="auth-form needs-validation" novalidate>
    <div id="soft-error" class="d-none pb-2 text-danger"></div>
    <input type="hidden" id="id" name="id" value="<?php if ( isset( $id ) ) echo $id ?>" />
    <input type="hidden" id="image_url" name="image_url" />
    <div class="form-group">
        <label for="image">Your Avatar</label>
        <input type="file" class="form-control d-none" id="image" name="image" accept="image/*" >
        <br>
        <div id="image_container" class="p-1 border d-inline-block">
            <div id="image_canvas">
                <img id="output_image" src="<?php echo $assets ?>images/placeholder.png" alt="Your avatar" />
            </div>
        </div>
    </div>
	<div class="form-group">
		<label for="name">Name</label>
		<input type="text" class="form-control" id="name" name="name" placeholder="Enter ship name..." required maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid ship name.
        </div>
	</div>
    <div class="form-group">
        <label for="serial_number">Serial Number</label>
        <input type="text" class="form-control" id="serial_number" name="serial_number" placeholder="Enter serial number..." required maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid serial number.
        </div>
    </div>
	<button type="submit" class="btn btn-primary">Save</button>
</form>
