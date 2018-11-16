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
        <?php if ($hResult->Description !== "") { ?>
            <img src="../media/img/icons/description.png" class="text-content__tools--blue modal__open--desc" alt="Edit icon" />
            <div class="modal__full--desc">
                <div class="modal__container">
                    <div class="modal__content">
                        <div class="modal__title">
                            <span><b>Reminder for <?php echo $employee->getFullName($user); ?> on <?php echo $date->getDateVerbal(); ?>:</b></span>                       
                        </div>
                        <div class="modal__desc"><?php echo $hResult->Description; ?></div>
                        <span class="modal__close">×</span>
                    </div>
                </div>
            </div>
        <?php } ?>

        <img src="../media/img/icons/edit.png" class="text-content__tools--green modal__open--edit" alt="Edit icon" />
        <div class="modal__full--edit">
            <div class="modal__container">
                <div class="modal__content">
                    <div class="modal__title">
                        <span><b>Edit hours for <?php echo $employee->getFullName($user); ?> on <?php echo $date->getDateVerbal(); ?>:</b></span>                        
                    </div>

                    <div class="modal__desc">
                        <form action="#hourModal" method="POST" autocomplete="off" class="modal__form">                                       
                            <input type="hidden" name="uid" value="<?php $emp->EmployeeID; ?>" />
                            <div class="modal-form--left">
                                <span class="modal-form__tag">Department:</span>
                                <select class="modal-form__input" name="department" placeholder="Department" >
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

                                <div class="modal-form__time">
                                    <span class="modal-form__tag modal-form__tag--time">Start Time:</span>
                                    <input class="modal-form__input modal-form__input--time" type="time" name="start" value="<?php echo escape(Input::get('start', $hours->getStart($hResult))); ?>" />
                                </div>
                                   
                                <div class="modal-form__time">
                                    <span class="modal-form__tag modal-form__tag--time">End Time:</span>
                                    <input class="modal-form__input modal-form__input--time" type="time" name="end" value="<?php echo escape(Input::get('stop', $hours->getEnd($hResult))); ?>" />
                                </div>
                                
                                <input type="hidden" name="date" value="<?php $date->getDate(); ?>" />
                                <span class="modal-form__tag">Description (optional):</span>
                                <input class="modal-form__input modal-form__input--desc" type="text" name="desc" value="<?php echo escape(Input::get('desc', $hResult->Description)); ?>" />
                            </div>
                            <button name="submit" class="modal-form__add">Update</button>
                        </form>
                    </div>
                    <span class="modal__close">×</span>
                </div>
            </div>
        </div>

        <img src="../media/img/icons/delete.png" alt="Delete icon" class="modal__open--del"/>   
        <div class="modal__full--del">
            <div class="modal__container">
                <div class="modal__content">   
                    <div class="modal__title">
                        <span><b>Are you sure you want to delete this shift?</b></span>                      
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