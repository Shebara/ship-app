<form id="rank" class="auth-form needs-validation" novalidate>
    <div id="soft-error" class="d-none pb-2 text-danger"></div>
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
        <label for="surname">Last Name</label>
        <input type="text" class="form-control" id="surname" name="surname" placeholder="Enter last name..." required maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid last name.
        </div>
    </div>
	<button type="submit" class="btn btn-primary">Save</button>
</form>
