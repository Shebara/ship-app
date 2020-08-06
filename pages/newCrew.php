<form id="crew" class="auth-form needs-validation" novalidate>
    <div id="soft-error" class="d-none pb-2 text-danger"></div>
    <input type="hidden" id="id" name="id" value="<?php if ( isset( $id ) ) echo $id ?>" />
    <div class="form-group">
        <label for="email">E-Mail</label>
        <input type="email" class="form-control" id="email" name="email" placeholder="Enter your e-mail..."
               required maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid e-mail.
        </div>
    </div>
	<div class="form-group">
		<label for="name">First Name</label>
		<input type="text" class="form-control" id="name" name="name" placeholder="Enter first name..." required maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid first name.
        </div>
	</div>
    <div class="form-group">
        <label for="rank">Rank</label>
        <select name="rank" id="rank" class="custom-select" required <?php if ( isset( $id ) && $id == 1 ) echo 'disabled' ?>>
            <option selected disabled value="">Select this crew member's rank...</option>
        </select>
        <div class="invalid-feedback">
            Please choose a rank.
        </div>
    </div>
    <div class="form-group">
        <label for="ship">Ship</label>
        <select name="ship" id="ship" class="custom-select">
            <option disabled selected>Select this crew member's ship...</option>
            <option value="">None</option>
        </select>
    </div>
	<button type="submit" class="btn btn-primary">Save</button>
</form>
