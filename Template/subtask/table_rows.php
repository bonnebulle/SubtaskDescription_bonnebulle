<!-- <?php print_r($csrf_token) ?> -->

<td class="sub_desc loading" data-taskid="<?= $task_id ?>" data-subid="<?= $subtask_id ?>" data-text="<?= $subtask["due_description"] ?>">
    <input type="checkbox" id="toggle_sub_desc_<?= $subtask_id ?>" class="hideme toggle_sub_desc_checkbox" />
    <form class="sub_desc_form" method="post" action="/assets/php/change_subdescription.php?task_id=<?= $task_id ?>&subtask_id=<?= $subtask_id ?>" autocomplete="off">
    <!-- <input class="hideme" type="hidden" name="csrf_token" value="<?= $csrf_token ?>"> -->
        <?php if (! empty($subtask['due_description'])): ?>
            <span class="wrap_desc">
                <?= $this->text->markdown($subtask['due_description']) ?>
            </span>
        <?php endif ?>
        <button type="submit" style="display:none"></button> <!-- bouton caché pour submit -->
    </form>
</td>
