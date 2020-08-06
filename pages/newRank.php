<?php
$id = isset( $id ) ? $id : FALSE;
$disabled = $id == 1;
?>
<form id="rank" class="auth-form needs-validation" novalidate>
    <div id="soft-error" class="d-none pb-2 text-danger"></div>
    <input type="hidden" id="id" name="id" value="<?php if ( $id === FALSE ) echo $id ?>" />
	<div class="form-group">
		<label for="name">Rank Name</label>
		<input type="text" class="form-control" id="name" name="name" placeholder="Enter rank name..." required
               maxlength="250" <?php if ( $disabled ) echo 'disabled' ?>>
        <div class="invalid-feedback">
            Please provide a valid rank name.
        </div>
	</div>
	<button type="submit" class="btn btn-primary" <?php if ( $disabled ) echo 'disabled' ?>>Save</button>
</form>
