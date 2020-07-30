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
    <a href="forgot" class="pt-3 d-block">Forgot your password? Reset it here!</a>
</form>
<div id="spinner" class="spinner-border d-none" role="status">
    <span class="sr-only">Loading...</span>
</div>
<div id="message" class="d-none">
    <h3>Error!</h3>
    <p>An unknown error has occurred.</p>
</div>