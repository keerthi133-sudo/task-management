<?php $__env->startSection('title', 'Tasks'); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="bi bi-list-task"></i> Task Management
        </h1>

        <!-- Filter Controls -->
        <div class="filter-controls">
            <form method="GET" action="<?php echo e(route('tasks.index')); ?>" class="row g-3">
                <div class="col-md-4">
                    <label for="status" class="form-label">Status Filter</label>
                    <select name="status" id="status" class="form-select" onchange="this.form.submit()">
                        <option value="all" <?php echo e(request('status') == 'all' ? 'selected' : ''); ?>>Show All</option>
                        <option value="active" <?php echo e(request('status') == 'active' ? 'selected' : ''); ?>>Active Only</option>
                        <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Completed Only</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="due" class="form-label">Due Date Filter</label>
                    <select name="due" id="due" class="form-select" onchange="this.form.submit()">
                        <option value="" <?php echo e(!request('due') ? 'selected' : ''); ?>>All Dates</option>
                        <option value="today" <?php echo e(request('due') == 'today' ? 'selected' : ''); ?>>Due Today</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <a href="<?php echo e(route('tasks.index')); ?>" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Clear Filters
                    </a>
                </div>
            </form>
        </div>

        <!-- Add Task Form -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-plus-circle"></i> Add New Task</h5>
            </div>
            <div class="card-body">
                <form action="<?php echo e(route('tasks.store')); ?>" method="POST" class="row g-3">
                    <?php echo csrf_field(); ?>
                    <div class="col-md-6">
                        <label for="title" class="form-label">Task Title</label>
                        <input type="text" class="form-control" id="title" name="title" 
                               value="<?php echo e(old('title')); ?>" required placeholder="Enter task title">
                    </div>
                    <div class="col-md-4">
                        <label for="due_date" class="form-label">Due Date</label>
                        <input type="date" class="form-control" id="due_date" name="due_date" 
                               value="<?php echo e(old('due_date')); ?>">
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus"></i> Add Task
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tasks Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-list-check"></i> Tasks 
                    <span class="badge bg-secondary"><?php echo e($tasks->count()); ?></span>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if($tasks->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th width="50">Status</th>
                                    <th>Title</th>
                                    <th width="150">Due Date</th>
                                    <th width="100">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $tasks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $task): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr class="task-row" data-task-id="<?php echo e($task->id); ?>">
                                        <td>
                                            <div class="form-check">
                                                <input class="form-check-input task-checkbox" 
                                                       type="checkbox" 
                                                       <?php echo e($task->is_completed ? 'checked' : ''); ?>

                                                       data-task-id="<?php echo e($task->id); ?>">
                                            </div>
                                        </td>
                                        <td>
                                            <input type="text" 
                                                   class="task-title-editable <?php echo e($task->is_completed ? 'task-completed' : ''); ?>" 
                                                   value="<?php echo e($task->title); ?>" 
                                                   data-task-id="<?php echo e($task->id); ?>"
                                                   data-original-value="<?php echo e($task->title); ?>">
                                        </td>
                                        <td>
                                            <?php if($task->due_date): ?>
                                                <span class="badge <?php echo e($task->due_date->isToday() ? 'bg-warning' : ($task->due_date->isPast() ? 'bg-danger' : 'bg-info')); ?>">
                                                    <?php echo e($task->due_date->format('M d, Y')); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">No due date</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <form action="<?php echo e(route('tasks.destroy', $task)); ?>" method="POST" 
                                                  class="d-inline delete-form">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                        onclick="return confirm('Are you sure you want to delete this task?')">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="text-center py-5">
                        <i class="bi bi-inbox display-1 text-muted"></i>
                        <h4 class="text-muted mt-3">No tasks found</h4>
                        <p class="text-muted">Add your first task using the form above.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
$(document).ready(function() {
    // Set up CSRF token for AJAX requests
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle checkbox toggle for task completion
    $('.task-checkbox').change(function() {
        const taskId = $(this).data('task-id');
        const isCompleted = $(this).is(':checked');
        const row = $(this).closest('tr');
        const titleInput = row.find('.task-title-editable');

        $.ajax({
            url: `/tasks/${taskId}`,
            method: 'PUT',
            data: {
                title: titleInput.val(),
                is_completed: isCompleted
            },
            success: function(response) {
                if (isCompleted) {
                    titleInput.addClass('task-completed');
                } else {
                    titleInput.removeClass('task-completed');
                }
            },
            error: function() {
                // Revert checkbox state on error
                $(this).prop('checked', !isCompleted);
                alert('Error updating task status');
            }
        });
    });

    // Handle inline editing of task titles
    $('.task-title-editable').on('blur keypress', function(e) {
        if (e.type === 'keypress' && e.which !== 13) {
            return; // Only proceed on Enter key or blur
        }

        const taskId = $(this).data('task-id');
        const newTitle = $(this).val().trim();
        const originalTitle = $(this).data('original-value');

        if (newTitle === originalTitle || newTitle === '') {
            $(this).val(originalTitle); // Revert to original if empty or unchanged
            return;
        }

        const checkbox = $(this).closest('tr').find('.task-checkbox');
        const isCompleted = checkbox.is(':checked');

        $.ajax({
            url: `/tasks/${taskId}`,
            method: 'PUT',
            data: {
                title: newTitle,
                is_completed: isCompleted
            },
            success: function(response) {
                $(this).data('original-value', newTitle);
                // Show brief success feedback
                $(this).addClass('border-success').removeClass('border-danger');
                setTimeout(() => {
                    $(this).removeClass('border-success');
                }, 1000);
            }.bind(this),
            error: function() {
                $(this).val(originalTitle); // Revert on error
                $(this).addClass('border-danger');
                setTimeout(() => {
                    $(this).removeClass('border-danger');
                }, 2000);
                alert('Error updating task title');
            }.bind(this)
        });
    });

    // Handle delete with AJAX
    $('.delete-form').submit(function(e) {
        e.preventDefault();
        
        if (!confirm('Are you sure you want to delete this task?')) {
            return;
        }

        const form = $(this);
        const row = form.closest('tr');

        $.ajax({
            url: form.attr('action'),
            method: 'DELETE',
            success: function() {
                row.fadeOut(300, function() {
                    $(this).remove();
                    // Update task count
                    const count = $('.task-row').length - 1;
                    $('.badge.bg-secondary').text(count);
                    
                    // Show empty state if no tasks left
                    if (count === 0) {
                        location.reload();
                    }
                });
            },
            error: function() {
                alert('Error deleting task');
            }
        });
    });
});
</script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\task-management-app\resources\views/tasks/index.blade.php ENDPATH**/ ?>