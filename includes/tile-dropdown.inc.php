<div class="cell__text-content">
    <span>
        <img src="../media/img/icons/clock.png" alt="Clock time icon" class="img-small" />
        <?php echo $hours->getStart($hResult); ?> - <?php echo $hours->getEnd($hResult); ?>
    </span>
    <span>
        <img src="../media/img/icons/sigma.png" alt="Total icon" class="img-small" />
        <?php echo $hours->getHours($hResult); ?> shift
    </span>
    <span>
        <img src="../media/img/icons/department.png" clock="Department icon" class="img-small" />
        <?php echo $hours->getDepartment($hResult->DepartmentID, $department); ?>
    </span> 

    <span class="text-content__tools">
        <!-- If there is a message, show the message icon to load message box -->
        <?php if ($hResult->Description !== "") { ?>
            <img src="../media/img/icons/description.png" class="text-content__tools--blue modal__open--desc" alt="Edit icon" />
            <div class="modal__full modal__full--desc">
                <div class="modal__container">
                    <div class="modal__content">
                        <div class="modal__title">
                            <span>Reminder for <?php echo $employee->getFullName($user); ?> on:</span>
                            <span><?php echo $date->getDateVerbal(); ?>:</span>                       
                        </div>
                        <div class="modal__desc"><?php echo $hResult->Description; ?></div>
                        <span class="modal__close">×</span>
                    </div>
                </div>
            </div>
        <?php } ?>


        <!-- Edit and update the requested hours -->
        <?php
            if ($date->getDate() >= $date->getToday()) { // we dont want to show the edit button, if the shift has already passed 
        ?>
            <img src="../media/img/icons/edit.png" class="text-content__tools--green modal__open--edit" alt="Edit icon" />
            <div class="modal__full modal__full--edit">
                <div class="modal__container">
                    <div class="modal__content">
                        <div class="modal__title">
                            <span>Edit hours for <?php echo $employee->getFullName($emp); ?> on:</span>
                            <span><?php echo $date->getDateVerbal(); ?></span>                        
                        </div>

                        <div class="modal__desc">
                            <!-- Try to figure out a way so that when it updates it takes you back to the modal which is still open? -->
                            <form action="" method="POST" autocomplete="off" class="modal__form">                                       
                                <input type="hidden" name="uid" value="<?php $emp->EmployeeID; ?>" />
                                <label class="modal-form__tag modal-form__tag--required"><span>*</span> Indicates required field</label>
                                <div class="modal-form--left">
                                    <!--Department field -->
                                    <div class="modal-form__field">
                                        <label class="modal-form__tag">Department <span>*</span></label>
                                        <select class="modal-form__input department" name="department" placeholder="Department" >
                                        <?php
                                            foreach ($department as $dep) {
                                                if ($dep->DepartmentID == $hResult->DepartmentID) {
                                        ?>
                                            <option value="<?php echo $dep->DepartmentID; ?>" selected="selected"><?php echo $dep->DepartmentName; ?></option>
                                        <?php
                                                } else {
                                        ?>
                                            <option value="<?php echo $dep->DepartmentID; ?>"><?php echo $dep->DepartmentName; ?></option>
                                        <?php   
                                                }
                                            }
                                        ?>
                                        </select> 
                                        <label class="modal-form__tag--error departmentError">* Choose a Department</label>
                                    </div>

                                    <!--Time fields -->
                                    <div class="modal-form__field">
                                        <div class="modal-form__field--time">
                                            <div class="modal-form__time">
                                                <label class="modal-form__tag modal-form__tag--time">Start Time <span>*</span></label>
                                                <input class="modal-form__input modal-form__input--time start" type="time" name="start" 
                                                    min="<?php echo $organization->getStart($company); ?>" max="<?php echo $organization->getStop($company); ?>" 
                                                    value="<?php echo $hours->getStart($hResult); ?>" />
                                                <label class="modal-form__tag--error startError">* Enter a valid Time</label>                                        
                                            </div>
                                            
                                            <div class="modal-form__time">
                                                <label class="modal-form__tag modal-form__tag--time">End Time <span>*</span></label>
                                                <input class="modal-form__input modal-form__input--time end" type="time" name="end" 
                                                    min="<?php echo $organization->getStart($company); ?>" max="<?php echo $organization->getStop($company); ?>" 
                                                    value="<?php echo $hours->getEnd($hResult); ?>" />
                                                <label class="modal-form__tag--error endError" >* Enter a valid Time</label>                                                                                            
                                            </div>
                                        </div>
                                        <label class="modal-form__tag--error timeError"></label>   
                                    </div>
                                    
                                    <!--Reminder field (optional)-->
                                    <div class="modal-form__field">
                                        <label class="modal-form__tag">Reminder</label>
                                        <input class="modal-form__input modal-form__input--desc" type="text" name="desc" value="<?php echo $hResult->Description; ?>" />
                                    </div>
                                </div>
                                <input type="hidden" name="id" value="<?php echo $hResult->BookID; ?>"/>
                                <button name="update" class="modal-form__add submit">Update</button>
                            </form>
                        </div>
                        <span class="modal__close">×</span>
                    </div>
                </div>
            </div>
        <?php
            }
        ?>


        <!-- Delete the requested hour -->
        <img src="../media/img/icons/delete.png" alt="Delete icon" class="modal__open--del"/>   
        <div class="modal__full modal__full--del">
            <div class="modal__container">
                <div class="modal__content">   
                    <div class="modal__title">
                        <span>Are you sure you want to delete this shift?</span>                      
                    </div>

                    <div class="modal__desc">
                        <form action="" method="POST" class="modal__form">
                            <input type="hidden" name="id" value="<?php echo $hResult->BookID; ?>"/>
                            <button name="delete" class="modal-form__add">Ok!</button>
                        </form>
                    </div>

                    <span class="modal__close">×</span>
                </div>
            </div>                    
        </div>    
    </span>
</div>