<?php $token = isset( $_GET[ 'token' ] ) ? $_GET[ 'token' ] : '' ?>
<form id="setPassword" class="auth-form needs-validation" data-token="<?php echo $token ?>" novalidate
      oninput='passwordCompare( password2, password, "Passwords do not match." );'>
    <p>Please type your new password twice to set it.</p>
    <div id="soft-error" class="d-none pb-2 text-danger"></div>
    <input type="hidden" id="id" name="id" />
    <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" class="form-control" id="password" name="password" placeholder="Enter password..."
               required minlength="3" maxlength="250">
        <div class="invalid-feedback">
            Please provide a valid password (at least 3, no longer than 250 characters).
        </div>
    </div>
    <div class="form-group">
        <label for="password2">Repeat Password</label>
        <input type="password" class="form-control" id="password2" name="password2" placeholder="Repeat password...">
        <div class="invalid-feedback">
            Passwords do not match.
        </div>
    </div>
    <button type="submit" class="btn btn-primary">Set</button>
</form>