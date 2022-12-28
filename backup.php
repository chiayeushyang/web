<div class='table-responsive'>
                    <table class='table table-hover table-responsive table-bordered'>
                        <tr>
                            <td>Username</td>
                            <td>
                                <div class="input-group input-group-lg mb-3">
                                    <span class="input-group-text" id="basic-addon1">@</span>
                                    <input type="text" class="form-control" name="username" value="<?php echo isset($username) ? $username : ""; ?>" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1" />
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type='password' name='password' class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Confirm Password</td>
                            <td><input type='password' name='confirm_password' class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Photo</td>
                            <td><input type="file" name="image" /></td>
                        </tr>
                        <tr>
                            <td>First name</td>
                            <td><input type='text' name='first_name' value="<?php echo isset($first_name) ? $first_name : ""; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Last name</td>
                            <td><input type='text' name='last_name' value="<?php echo isset($last_name) ? $last_name : ""; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Gender</td>
                            <td class="d-flex">
                                <div class="form-check mx-3">
                                    <input class="form-check-input" type="radio" name="gender" value="Male" id="Male" required <?php echo ((isset($gender)) && ($gender == 'Male')) ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Male">
                                        Male
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" value="Female" id="Female" required <?php echo ((isset($gender)) && ($gender == 'Female')) ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Female">
                                        Female
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Date of birth</td>
                            <td><input type='date' name='date_of_birth' value="<?php echo isset($date_of_birth) ? $date_of_birth : ""; ?>" class='form-control' /></td>
                        </tr>
                        <tr>
                            <td>Account status</td>
                            <td class="d-flex">
                                <div class="form-check mx-3">
                                    <input class="form-check-input" type="radio" name="account_status" value="Active" id="Active" required <?php echo ((isset($account_status)) && ($account_status == 'Active')) ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Active">
                                        Active
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="account_status" value="Inactive" id="Inactive" <?php echo ((isset($account_status)) && ($account_status == 'Inactive')) ?  "checked" : "";  ?>>
                                    <label class="form-check-label" for="Inactive">
                                        Inactive
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <input type='submit' value='Save' class='btn btn-primary' />
                                <a href='welcome_page.php' class='btn btn-danger'>Back to home</a>
                            </td>
                        </tr>
                    </table>
                </div>