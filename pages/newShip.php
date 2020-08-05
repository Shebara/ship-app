<form id="rank" class="auth-form needs-validation" novalidate>
    <div id="soft-error" class="d-none pb-2 text-danger"></div>
	<div class="form-group">
		<label for="name">Name</label>
		<input type="text" class="form-control" id="name" name="name" placeholder="Enter rank name..." required maxlength="250">
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
    <!--TODO image upload-->
	<button type="submit" class="btn btn-primary">Save</button>
</form>
