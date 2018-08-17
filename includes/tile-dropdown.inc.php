<div class="text-contents">
    <span>
        <img src="media/img/clock.png" alt="Clock time icon" class="img-small" />
        <?php echo $bookedHours->getStart(); ?> - <?php echo $bookedHours->getEnd(); ?>
    </span>
    <span>
        <img src="media/img/sigma.png" alt="Total icon" class="img-small" />
        <?php echo $bookedHours->getHours();?> shift
    </span>
    <span>
        <img src="media/img/department.png" clock="Department icon" class="img-small" />
        <?php echo $bookedHours->getDepartment(); ?>
    </span> 
    <span class="more-info-tools">
        <img src="media/img/edit.png" alt="Edit icon" />
        <img src="media/img/delete.png" alt="Delete icon" />
    </span>
</div>