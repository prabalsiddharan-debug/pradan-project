<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="customer_profile">
    <div class="content">
        <div class="md:tw-w-[calc(100%-theme(width.64)+theme(spacing.16))] [&_div:last-child]:tw-mb-6">
            <?php if (isset($client) && $client->registration_confirmed == 0 && is_admin()) { ?>
            <div class="alert alert-warning">
                <h4>
                    <?= _l('customer_requires_registration_confirmation'); ?>
                </h4>
                <a href="<?= admin_url('clients/confirm_registration/' . $client->userid); ?>"
                    class="alert-link">
                    <?= _l('confirm_registration'); ?>
                </a>
            </div>
            <?php } elseif (isset($client) && $client->active == 0 && $client->registration_confirmed == 1) { ?>
            <div class="alert alert-warning">
                <?= _l('customer_inactive_message'); ?>
                <br />
                <a href="<?= admin_url('clients/mark_as_active/' . $client->userid); ?>"
                    class="alert-link">
                    <?= _l('mark_as_active'); ?>
                </a>
            </div>
            <?php } ?>
            <?php if (isset($client) && (staff_cant('view', 'customers') && is_customer_admin($client->userid))) {?>
            <div class="alert alert-info">
                <?= e(_l('customer_admin_login_as_client_message', get_staff_full_name(get_staff_user_id()))); ?>
            </div>
            <?php } ?>
        </div>

        <?php if (isset($client) && $client->leadid != null) { ?>
        <small class="tw-block">
            <b><?= e(_l('customer_from_lead', _l('lead'))); ?></b>
            <a href="<?= admin_url('leads/index/' . $client->leadid); ?>"
                onclick="init_lead(<?= e($client->leadid); ?>); return false;">
                -
                <?= _l('view'); ?>
            </a>
        </small>
        <?php } ?>

        <div class="md:tw-max-w-64 tw-w-full">
            <?php if (isset($client)) { ?>
            <h4 class="tw-text-lg tw-font-bold tw-text-neutral-800 tw-mt-0">
                <div class="tw-space-x-3 tw-flex tw-items-center">
                    <span class="tw-truncate">
                        #<?= e($client->userid . ' ' . $title); ?>
                    </span>
                    <?php if (staff_can('delete', 'customers') || is_admin()) { ?>
                    <div class="btn-group">
                        <a href="#" class="dropdown-toggle btn-link" data-toggle="dropdown" aria-haspopup="true"
                            aria-expanded="false">
                            <span class="caret"></span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-right">
                            <?php if (is_admin()) { ?>
                            <li>
                                <a href="<?= admin_url('clients/login_as_client/' . $client->userid); ?>"
                                    target="_blank">
                                    <i class="fa-regular fa-share-from-square"></i>
                                    <?= _l('login_as_client'); ?>
                                </a>
                            </li>
                            <?php } ?>
                            <?php if (staff_can('delete', 'customers')) { ?>
                            <li>
                                <a href="<?= admin_url('clients/delete/' . $client->userid); ?>"
                                    class="text-danger delete-text _delete"><i class="fa fa-remove"></i>
                                    <?= _l('delete'); ?>
                                </a>
                            </li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?>
                </div>
            </h4>
            <?php } ?>
        </div>

        <div class="md:tw-flex md:tw-gap-6">
            <?php if (isset($client)) { ?>
            <div class="md:tw-max-w-64 tw-w-full">
                <?php $this->load->view('admin/clients/tabs'); ?>
            </div>
            <?php } ?>
            <div
                class="tw-mt-12 md:tw-mt-0 tw-w-full <?= isset($client) ? 'tw-max-w-6xl' : 'tw-mx-auto tw-max-w-4xl'; ?>">

                <?php if (! isset($client)) {?>
                <h4 class="tw-mt-0 tw-font-bold tw-text-lg tw-text-neutral-700">
                    <?= $title ?>
                </h4>
                <?php } ?>

                <div class="panel_s">
                    <!-- <?php if (isset($client) && $group == 'profile') { ?>
                    <div class="panel-heading" style="display:flex;justify-content:flex-end;padding:10px 15px;">
                        <button type="button" class="btn btn-info btn-sm" id="btn-edit-client" onclick="enableClientEditMode();">
                            <i class="fa fa-pencil"></i> Edit
                        </button>
                        <button type="button" class="btn btn-default btn-sm" id="btn-cancel-edit-client" style="display:none;margin-left:5px;" onclick="disableClientEditMode();">
                            <i class="fa fa-times"></i> Cancel
                        </button>
                    </div>
                    <?php } ?> -->
                   
                    <div class="panel-body">
                        <?php if (isset($client)) { ?>
                        <?= form_hidden('isedit'); ?>
                        <?= form_hidden('userid', $client->userid); ?>
                        <div class="clearfix"></div>
                        <?php } ?>
                        <div>
                            <div class="tab-content">
                                <?php $this->load->view((isset($tab) ? $tab['view'] : 'admin/clients/groups/profile')); ?>
                            </div>
                        </div>
                        <hr>

<div class="client-action-bar">

    <div class="pull-left">

        <a href="<?= admin_url('clients/client'); ?>"
           class="btn btn-default">
            <i class="fa fa-plus"></i> New
        </a>

        <a href="<?= admin_url('clients'); ?>"
           class="btn btn-default">
            <i class="fa fa-list"></i> View
        </a>

    </div>

    <?php if(isset($client)){ ?>

    <div class="pull-right">

        <button
            type="button"
            class="btn btn-primary"
            id="btn-edit-client"
            onclick="enableClientEditMode();">

            <i class="fa fa-pencil"></i> Edit

        </button>

        <button
            type="button"
            class="btn btn-success"
            id="btn-save-client"
            style="display:none;"
            onclick="$('.only-save').click();">

            <i class="fa fa-save"></i> Save

        </button>

        <button
            type="button"
            class="btn btn-warning"
            id="btn-clear-client"
            style="display:none;"
            onclick="clearClientForm();">

            <i class="fa fa-eraser"></i> Clear

        </button>

        <button
            type="button"
            class="btn btn-default"
            id="btn-cancel-edit-client"
            style="display:none;"
            onclick="disableClientEditMode();">

            Cancel

        </button>

        <?php if(staff_can('delete','customers')){ ?>

        <a href="<?= admin_url('clients/delete/'.$client->userid); ?>"
           class="btn btn-danger _delete">

            Delete

        </a>

        <?php } ?>

    </div>

    <?php } ?>

    <div class="clearfix"></div>

</div>
                    </div>
                    <?php if ($group == 'profile') { ?>
                    <div class="panel-footer text-right tw-space-x-1" id="profile-save-section" style="display:none;">
                        <?php if (! isset($client)) { ?>
                        <button class="btn btn-default save-and-add-contact customer-form-submiter">
                            <?= _l('save_customer_and_add_contact'); ?>
                        </button>
                        <?php } ?>
                        <button class="btn btn-primary only-save customer-form-submiter">
                            <?= _l('submit'); ?>
                        </button>
                    </div>
                     <!-- <?php if ($group == 'profile') { ?>
                    <div class="panel-footer text-right tw-space-x-1" id="profile-save-section" <?php if (isset($client)) { ?>style="display:none;"<?php } ?>>
                    <div class="panel-footer text-right tw-space-x-1" id="profile-save-section" style="display:none;">
                        <?php if (! isset($client)) { ?>
                        <button class="btn btn-default save-and-add-contact customer-form-submiter">
                        <button class="btn btn-default save-and-add-contact customer-form-submiter" style="display:none;">
                            <?= _l('save_customer_and_add_contact'); ?>
                        </button>
                        <?php } ?>
                        <button class="btn btn-primary only-save customer-form-submiter">
                        <button class="btn btn-primary only-save customer-form-submiter" style="display:none;">
                            <?= _l('submit'); ?>
                        </button>
                    </div>
                    <div class="panel-footer text-center tw-space-x-2" style="display: flex; justify-content: center; gap: 10px;">
                        <a href="<?= admin_url('clients/client'); ?>" class="btn btn-primary">New</a>
                        <?php if (isset($client)) { ?>
                            <button type="button" class="btn btn-info" onclick="enableClientEditMode();">Edit</button>
                        <?php } else { ?>
                            <button type="button" class="btn btn-info" disabled>Edit</button>
                        <?php } ?>
                        <a href="<?= admin_url('clients'); ?>" class="btn btn-success">View</a>
                        <button type="button" class="btn btn-warning" onclick="$('.customer-form-submiter.only-save').click();">Save</button>
                        <a href="<?= admin_url('clients/client' . (isset($client) ? '/' . $client->userid : '')); ?>" class="btn btn-default">Clear</a>
                        <?php if (isset($client) && staff_can('delete', 'customers')) { ?>
                            <a href="<?= admin_url('clients/delete/' . $client->userid); ?>" class="btn btn-danger _delete">Delete</a>
                        <?php } else { ?>
                            <button type="button" class="btn btn-danger" disabled>Delete</button>
                        <?php } ?>
                    </div>
                    <?php } ?>
                </div>
            </div> -->
                    <?php } ?>
                </div>
            </div>
        </div>

    </div>
</div>
<?php init_tail(); ?>
<?php if (isset($client)) { ?>
<script>
    $(function() {
        init_rel_tasks_table( <?= e($client->userid); ?> ,
            'customer');

        // Start in view mode for existing clients on profile tab
        <?php if ($group == 'profile') { ?>
        disableClientEditMode();
        <?php } ?>
    });

    // function enableClientEditMode() {
    //     var $form = $('.client-form');
    //     $form.find('input:not([type=hidden]), textarea, select').prop('disabled', false);
    //     // Re-initialize selectpicker dropdowns after enabling
    //     $form.find('select.selectpicker').selectpicker('refresh');

    //     $('#profile-save-section').slideDown(200);
    //     $('#btn-edit-client').hide();
    //     $('#btn-cancel-edit-client').show();
    // }

    // function disableClientEditMode() {
    //     var $form = $('.client-form');
    //     $form.find('input:not([type=hidden]), textarea, select').prop('disabled', true);
    //     // Re-initialize selectpicker dropdowns after disabling
    //     $form.find('select.selectpicker').selectpicker('refresh');

    //     $('#profile-save-section').slideUp(200);
    //     $('#btn-edit-client').show();
    //     $('#btn-cancel-edit-client').hide();
    // }
    function enableClientEditMode() {

    var $form = $('.client-form');

    $form.find('input:not([type=hidden]), textarea, select')
         .prop('disabled', false);

    $form.find('select.selectpicker').selectpicker('refresh');

    //$('#profile-save-section').show();

    $('#btn-edit-client').hide();
    $('#btn-save-client').show();
    $('#btn-clear-client').show();
    $('#btn-cancel-edit-client').show();
}

function disableClientEditMode() {

    var $form = $('.client-form');

    $form.find('input:not([type=hidden]), textarea, select')
         .prop('disabled', true);

    $form.find('select.selectpicker').selectpicker('refresh');

    //$('#profile-save-section').hide();

    $('#btn-edit-client').show();
    $('#btn-save-client').hide();
    $('#btn-clear-client').hide();
    $('#btn-cancel-edit-client').hide();
}

function clearClientForm() {

    $('.client-form').find(
        'input:not([type=hidden],[type=button],[type=submit],[type=reset])'
    ).val('');

    $('.client-form textarea').val('');

    $('.client-form select').prop('selectedIndex', 0);

    $('.client-form .selectpicker').selectpicker('refresh');
}
</script>
<?php } ?>
<?php $this->load->view('admin/clients/client_js'); ?>
</body>

</html>