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
        <img src="media/img/icons/edit.png" alt="Edit icon" />
        <img src="media/img/icons/delete.png" alt="Delete icon" />
    </span>
</div>