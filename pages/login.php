<form id="login" class="auth-form needs-validation" novalidate>
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
		<label for="password">Password</label>
		<input type="password" class="form-control" id="password" name="password" placeholder="Enter password..."
               required minlength="3" maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid password.
        </div>
	</div>
	<div class="form-check pb-3">
		<input type="checkbox" class="form-check-input" id="remember" name="remember">
		<label class="form-check-label" for="remember">Stay logged in</label>
	</div>
	<button type="submit" class="btn btn-primary">Log In</button>
</form>
