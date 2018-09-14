<div class="cell__text-content">
    <span>
        <img src="media/img/icons/clock.png" alt="Clock time icon" class="img-small" />
        <?php echo $bookedHours->getStart(); ?> - <?php echo $bookedHours->getEnd(); ?>
    </span>
    <span>
        <img src="media/img/icons/sigma.png" alt="Total icon" class="img-small" />
        <?php echo $bookedHours->getHours();?> shift
    </span>
    <span>
        <img src="media/img/icons/department.png" clock="Department icon" class="img-small" />
        <?php echo $bookedHours->getDepartment(); ?>
    </span> 

    <span class="text-content__tools">
        <?php if ($bookedHours->getDesc() !== "") { ?>
            <img src="media/img/icons/description.png" class="text-content__tools--blue" alt="Edit icon" />
            <div class="text-content__desc">
                <div class="desc__container">
                    <div class="desc__content">
                        <div class="desc__title">
                            <span><b>Reminder for <?php echo $employee->getName("full"); ?> on '<?php echo $bookedHours->getDate(true); ?>':</b><span>                       
                        </div>
                        <span class="desc__desc"><?php echo $bookedHours->getDesc(); ?></span>
                        <span class="desc__close">×</span>
                    </div>
                </div>
            </div>
        <?php } ?>
        <img src="media/img/icons/edit.png" class="text-content__tools--green" alt="Edit icon" />
        <img src="media/img/icons/delete.png" alt="Delete icon" />
    </span>
</div>