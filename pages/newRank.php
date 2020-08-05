<form id="rank" class="auth-form needs-validation" novalidate>
    <div id="soft-error" class="d-none pb-2 text-danger"></div>
	<div class="form-group">
		<label for="name">Rank Name</label>
		<input type="text" class="form-control" id="name" name="name" placeholder="Enter rank name..." required maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid rank name.
        </div>
	</div>
	<button type="submit" class="btn btn-primary">Save</button>
</form>
