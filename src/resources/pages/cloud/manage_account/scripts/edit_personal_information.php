<?PHP
    use DynamicalWeb\DynamicalWeb;
    use DynamicalWeb\HTML;

?>
<h4 class="mt-2 pt-4 mb-4">Personal Details</h4>
<form method="POST" action="<?PHP DynamicalWeb::getRoute('cloud/manage_account', array('action'=>'update_information', 'id'=>$_GET['id']), true); ?>">
    <div class="row">
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="first_name">First Name</label>
                <div class="col-sm-9">
                    <input type="text"<?PHP HTML::print(USER_FIRST_NAME, false); ?> name="first_name" id="first_name" class="form-control" placeholder="None">
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label" for="last_name">Last Name</label>
                <div class="col-sm-9">
                    <input type="text"<?PHP HTML::print(USER_LAST_NAME, false); ?> name="last_name" id="last_name" class="form-control" placeholder="None">
                </div>
            </div>
        </div>
    </div>
    <div class="row pt-3">
        <div class="col-md-12">
            <div class="form-group row">
                <label class="col-sm-3 col-form-label mb-4" for="first_name">Birthday</label>
                <div class="col-sm-3">
                    <label for="dob_month">Month</label>
                    <select class="form-control border-primary" id="dob_month" name="dob_month">
                        <option value="1"<?PHP if(USER_BOD_MONTH == 1){ HTML::print("selected=\selected\"", false); } ?>>January</option>
                        <option value="2"<?PHP if(USER_BOD_MONTH == 2){ HTML::print("selected=\selected\"", false); } ?>>February</option>
                        <option value="3"<?PHP if(USER_BOD_MONTH == 3){ HTML::print("selected=\selected\"", false); } ?>>March</option>
                        <option value="4"<?PHP if(USER_BOD_MONTH == 4){ HTML::print("selected=\selected\"", false); } ?>>April</option>
                        <option value="5"<?PHP if(USER_BOD_MONTH == 5){ HTML::print("selected=\selected\"", false); } ?>>May</option>
                        <option value="6"<?PHP if(USER_BOD_MONTH == 6){ HTML::print("selected=\selected\"", false); } ?>>June</option>
                        <option value="7"<?PHP if(USER_BOD_MONTH == 7){ HTML::print("selected=\selected\"", false); } ?>>July</option>
                        <option value="8"<?PHP if(USER_BOD_MONTH == 8){ HTML::print("selected=\selected\"", false); } ?>>August</option>
                        <option value="9"<?PHP if(USER_BOD_MONTH == 9){ HTML::print("selected=\selected\"", false); } ?>>September</option>
                        <option value="10<?PHP if(USER_BOD_MONTH == 10){ HTML::print("selected=\selected\"", false); } ?>">October</option>
                        <option value="11"<?PHP if(USER_BOD_MONTH == 11){ HTML::print("selected=\selected\"", false); } ?>>November</option>
                        <option value="12"<?PHP if(USER_BOD_MONTH == 12){ HTML::print("selected=\selected\"", false); } ?>>December</option>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="dob_day">Day</label>
                    <select class="form-control border-primary" id="dob_day" name="dob_day">
                        <?PHP
                        $FirstDay = 1;
                        $MaxDay = 31;
                        $CurrentCount = $FirstDay;

                        while(true)
                        {
                            if($CurrentCount > $MaxDay)
                            {
                                break;
                            }
                            if(USER_BOD_DAY == $CurrentCount)
                            {
                                HTML::print("<option value=\"" . $CurrentCount . "\"  selected=\"selected\">" . $CurrentCount . "</option>", false);
                            }
                            else
                            {
                                HTML::print("<option value=\"" . $CurrentCount . "\">" . $CurrentCount . "</option>", false);
                            }
                            $CurrentCount += 1;
                        }
                        ?>
                    </select>
                </div>
                <div class="col-sm-3">
                    <label for="dob_year">Year</label>
                    <select class="form-control border-primary" id="dob_year" name="dob_year">
                        <?PHP
                        $FirstYear = 1970;
                        $CurrentYear = (int)date('Y') - 13;
                        $CurrentCount = $FirstYear;

                        while(true)
                        {
                            if($CurrentCount > $CurrentYear)
                            {
                                break;
                            }
                            if(USER_BOD_YEAR == $CurrentCount)
                            {
                                HTML::print("<option value=\"" . $CurrentCount . "\" selected=\"selected\">" . $CurrentCount . "</option>", false);
                            }
                            else
                            {
                                HTML::print("<option value=\"" . $CurrentCount . "\">" . $CurrentCount . "</option>", false);
                            }
                            $CurrentCount += 1;
                        }
                        ?>
                    </select>
                </div>


            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="form-group row">
                <div class="col-sm-3">
                    <input type="submit" class="btn btn-sm btn-outline-primary" value="Save Changes">
                </div>
            </div>
        </div>
    </div>
</form>
