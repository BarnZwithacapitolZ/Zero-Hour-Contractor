<div class="cell__text-content">
    <span>
        <img src="../media/img/icons/clock.png" alt="Clock time icon" class="img-small" />
        <?php echo $bookedHours->getStart(); ?> - <?php echo $bookedHours->getEnd(); ?>
    </span>
    <span>
        <img src="../media/img/icons/sigma.png" alt="Total icon" class="img-small" />
        <?php echo $bookedHours->getHours();?> shift
    </span>
    <span>
        <img src="../media/img/icons/department.png" clock="Department icon" class="img-small" />
        <?php echo $bookedHours->getDepartment(); ?>
    </span> 

    <span class="text-content__tools">
        <?php if ($bookedHours->getDesc() !== "") { ?>
            <img src="../media/img/icons/description.png" class="text-content__tools--blue modal__open--desc" alt="Edit icon" />
            <div class="modal__full--desc">
                <div class="modal__container">
                    <div class="modal__content">
                        <div class="modal__title">
                            <span><b>Reminder for <?php echo $employee->getName("full"); ?> on <?php echo $bookedHours->getDate(true); ?>:</b></span>                       
                        </div>
                        <div class="modal__desc"><?php echo $bookedHours->getDesc(); ?></div>
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
                        <span><b>Edit hours for <?php echo $employee->getName("full"); ?> on <?php echo $date->getDateVerbal(); ?>:</b></span>                        
                    </div>

                    <div class="modal__desc">
                        <form action="#hourModal" method="POST" autocomplete="off" class="modal__form">                                       
                            <input type="hidden" name="uid" value="<?php $employee->getID(); ?>" />
                            <div class="modal-form--left">
                                <span class="modal-form__tag">Department:</span>
                                <select class="modal-form__input" name="department" placeholder="Department" >
                                <?php
                                    foreach ($result as $dep) {
                                        if ($dep->DepartmentName == $bookedHours->getDepartment()) {
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
                                    <input class="modal-form__input modal-form__input--time" type="time" name="start" value="<?php echo escape(Input::get('start', $bookedHours->getStart())); ?>" />
                                </div>
                                   
                                <div class="modal-form__time">
                                    <span class="modal-form__tag modal-form__tag--time">End Time:</span>
                                    <input class="modal-form__input modal-form__input--time" type="time" name="end" value="<?php echo escape(Input::get('stop', $bookedHours->getEnd())); ?>" />
                                </div>
                                
                                <input type="hidden" name="date" value="<?php $date->getDate(); ?>" />
                                <span class="modal-form__tag">Description (optional):</span>
                                <input class="modal-form__input modal-form__input--desc" type="text" name="desc" value="<?php echo escape(Input::get('desc', $bookedHours->getDesc())); ?>" />
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
                            <input type="hidden" name="id" value="<?php echo $bookedHours->getID(); ?>"/>
                            <button name="delete" class="modal-form__add">Ok!</button>
                        </form>
                    </div>

                    <span class="modal__close">×</span>
                </div>
            </div>                    
        </div>    
    </span>
</div>