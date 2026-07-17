<?php
defined('BASEPATH') or exit('No direct script access allowed');

// This partial is rendered for both new and existing customers.  Normalize
// controller-provided view data so editors can analyse this file safely.
$viewData         = get_defined_vars();
$clientObject     = isset($viewData['client']) && is_object($viewData['client']) ? $viewData['client'] : false;
$isExistingClient = $clientObject !== false;
$clientData       = $isExistingClient ? get_object_vars($clientObject) : [];
$clientValue      = static function (string $field, $default = '') use ($clientData) {
    return $clientData[$field] ?? $default;
};
$groups           = isset($viewData['groups']) && is_array($viewData['groups']) ? $viewData['groups'] : [];
$customer_groups  = isset($viewData['customer_groups']) && is_array($viewData['customer_groups']) ? $viewData['customer_groups'] : [];
$customer_admins  = isset($viewData['customer_admins']) && is_array($viewData['customer_admins']) ? $viewData['customer_admins'] : [];
$countries        = get_all_countries();
?>
<style>
/* Customer entry: scoped styles that complement the active CRM theme. */
.customer-profile .panel_s { border: 1px solid #dbe5f1; border-radius: 14px; overflow: hidden; box-shadow: 0 12px 30px rgba(20, 50, 92, .08); }
.customer-profile .panel_s > .panel-body { padding: 24px; background: #fff; }
.customer-profile .customer-info-header { background: linear-gradient(135deg, #174d8d, #2674c9) !important; box-shadow: 0 10px 24px rgba(23, 77, 141, .18); }
.customer-profile .customer-step-tabs { background: #4d8dd1; border-bottom: 0 !important; padding: 0 14px !important; }
.customer-profile .customer-step-tabs > li > a { color: #fff !important; border: 0 !important; border-bottom-color: transparent !important; border-radius: 8px 8px 0 0; font-weight: 600; padding: 13px 18px; }
.customer-profile .customer-step-tabs > li > a i { color: #fff !important; }
.customer-profile .customer-step-tabs > li.active > a { color: #174d8d !important; background: #fff !important; border-bottom-color: transparent !important; box-shadow: inset 0 -3px 0 #249b73; }
.customer-profile .customer-step-tabs > li.active > a i { color: #174d8d !important; }
.customer-profile .customer-step-tabs > li > a:hover { color: #fff !important; background: rgba(255,255,255,.14) !important; }
.customer-profile .customer-step-tabs > li > a:hover i { color: #fff !important; }
.customer-profile .customer-section { background: #f7faff; border-left: 4px solid #2674c9; padding: 14px 18px; margin-bottom: 18px; border-radius: 8px; }
.customer-profile .customer-section--address { border-left-color: #249b73; }
.customer-profile .customer-section h4 { color: #174d8d; margin: 0 0 3px; font-weight: 700; }
.customer-profile .customer-section small { color: #60738a; }
.customer-profile .customer-form .form-group { margin-bottom: 16px; }
.customer-profile #wrapper .customer-form .form-group > label,
.customer-profile #wrapper .customer-form .form-group > label.control-label { flex: 0 0 24% !important; max-width: 24% !important; padding-right: 8px !important; }
.customer-profile .customer-form label { color: #344d68 !important; font-weight: 600; }
.customer-profile .customer-form .form-control { border-color: #cbd9e8; border-radius: 7px; box-shadow: none; }
.customer-profile .customer-form .form-control:focus { border-color: #2674c9; box-shadow: 0 0 0 3px rgba(38,116,201,.12); }
.customer-profile .customer-address-fields { align-items: flex-end; }
.customer-profile .customer-address-fields .form-group { margin-bottom: 0; }
.customer-profile .customer-entry-intro { display: flex; align-items: center; gap: 14px; margin-bottom: 18px; padding: 18px 22px; border: 1px solid #cfe0f4; border-radius: 14px; background: linear-gradient(105deg, #f8fbff, #eef7f4); }
.customer-profile .customer-entry-intro__icon { display: grid; place-items: center; width: 42px; height: 42px; color: #fff; background: linear-gradient(135deg, #174d8d, #2674c9); border-radius: 11px; font-size: 18px; }
.customer-profile .customer-entry-intro__icon i { color: #fff !important; }
.customer-profile .customer-entry-intro h3 { margin: 0 0 3px; color: #174d8d; font-weight: 700; }
.customer-profile .customer-entry-intro p { margin: 0; color: #60738a; }
.customer-profile .customer-action-bar { display: flex; align-items: center; justify-content: flex-end; gap: 9px; padding: 14px 20px; border-top: 1px solid #dbe5f1; background: #f8fbff; }
.customer-profile .customer-action { border-radius: 7px; font-weight: 600; }
.customer-profile .customer-action--primary { border-color: #174d8d; background: #174d8d; }
.customer-profile .customer-action--primary:hover { border-color: #123f74; background: #123f74; }
.customer-profile .customer-action--secondary { margin-right: auto; }
.customer-profile #billing_and_shipping h4,
.customer-profile #billing_and_shipping h4 span,
.customer-profile #billing_and_shipping h4 a { color: #fff !important; }
@media (max-width: 991px) { .customer-profile .customer-address-fields .form-group { margin-bottom: 16px; } }
@media (max-width: 768px) { .customer-profile #wrapper .customer-form .form-group > label, .customer-profile #wrapper .customer-form .form-group > label.control-label { flex: 1 !important; max-width: 100% !important; } .customer-profile .customer-action-bar { flex-wrap: wrap; } .customer-profile .customer-action--secondary { margin-right: 0; } }
</style>

<div class="row">
    <?= form_open($this->uri->uri_string(), ['class' => 'client-form', 'autocomplete' => 'off']); ?>
    <div class="additional"></div>

    <!-- Customer Code Header -->
    <div class="customer-info-header" style="background: linear-gradient(135deg, #1e3c72, #2a5298); padding: 12px 24px; margin-bottom: 25px; border-radius: 8px; color: #fff;">
        <div class="row">
            <div class="col-md-12">
                <div style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;">
                    <div>
                        <span style="background:rgba(255,255,255); padding: 6px 12px; border-radius: 20px; font-weight: 600;">
                            <i class="fa fa-id-card"></i> Customer Code
                        </span>
                    </div>
                    <?php
                    $id_to_show = 'NEW';
                    if ($isExistingClient) {
                        $id_to_show = $clientValue('userid');
                    } else {
                        $next_id_query = $this->db->select_max('userid')->get(db_prefix() . 'clients')->row();
                        $id_to_show = $next_id_query ? ($next_id_query->userid + 1) : 1;
                    }
                    ?>
                    <span style="background:#fff; color:#1e3c72; font-weight:700; padding: 6px 18px; border-radius: 6px; font-size: 18px;">
                        <?= $id_to_show; ?>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="horizontal-scrollable-tabs panel-full-width-tabs">
            <div class="horizontal-tabs">
                <ul class="nav nav-tabs customer-profile-tabs nav-tabs-horizontal customer-step-tabs" role="tablist">
                    <li role="presentation" class="<?= !$this->input->get('tab') || $this->input->get('tab') == 'contact_info' ? 'active' : ''; ?>">
                        <a href="#contact_info" aria-controls="contact_info" role="tab" data-toggle="tab">
                            <i class="fa fa-user"></i>&nbsp; Customer Details
                        </a>
                    </li>
                    <li role="presentation" class="<?= $this->input->get('tab') == 'gst_details' ? 'active' : ''; ?>">
                        <a href="#gst_details" aria-controls="gst_details" role="tab" data-toggle="tab">
                            <i class="fa fa-file-text"></i> &nbsp; GST Details
                        </a>
                    </li>
                    <li role="presentation" class="<?= $this->input->get('tab') == 'billing_and_shipping' ? 'active' : ''; ?>">
                        <a href="#billing_and_shipping" aria-controls="billing_and_shipping" role="tab" data-toggle="tab">
                            <i class="fa fa-map-marker"></i> &nbsp; Billing & Shipping
                        </a>
                    </li>
                    <?php if (total_rows(db_prefix() . 'customfields', ['fieldto' => 'customers', 'active' => 1]) > 0) { ?>
                        <li role="presentation" class="<?= $this->input->get('tab') == 'custom_fields' ? 'active' : ''; ?>">
                            <a href="#custom_fields" aria-controls="custom_fields" role="tab" data-toggle="tab">
                                <?= hooks()->apply_filters('customer_profile_tab_custom_fields_text', _l('custom_fields')); ?>
                            </a>
                        </li>
                    <?php } ?>
                    <?php hooks()->do_action('after_customer_billing_and_shipping_tab', $clientObject); ?>

                    <?php if ($isExistingClient) { ?>
                        <li role="presentation">
                            <a href="#customer_admins" aria-controls="customer_admins" role="tab" data-toggle="tab">
                                <i class="fa fa-users"></i>&nbsp; Customer Admin
                                <?php if (count($customer_admins) > 0) { ?>
                                    <span class="badge bg-default"><?= count($customer_admins) ?></span>
                                <?php } ?>
                            </a>
                        </li>
                    <?php } ?>
                </ul>
            </div>
        </div>

        <div class="tab-content mtop15">

            <!-- ====================== CUSTOMER DETAILS TAB ====================== -->
            <div role="tabpanel" class="tab-pane<?= !$this->input->get('tab') || $this->input->get('tab') == 'contact_info' ? ' active' : ''; ?>" id="contact_info">
                <div class="row">

                    <!-- Customer Information -->
                    <div class="col-md-12">
                        <div class="customer-section">
                            <h4><i class="fa fa-user-circle"></i> Customer Information</h4>
                            <small>Enter the customer's basic information.</small>
                        </div>
                    </div>

                    <!-- Row 1: 3 Fields -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <?php $title_value = $clientValue('title'); ?>
                                <?= render_select('title', [
                                    ['id'=>'','name'=>'Select Title'],
                                    ['id'=>'Mr','name'=>'Mr'],
                                    ['id'=>'Mrs','name'=>'Mrs'],
                                    ['id'=>'M/s','name'=>'M/s']
                                ], ['id','name'], 'Title', $title_value); ?>
                            </div>
                            <div class="col-md-5">
                                <?= render_input('company', 'client_company', $clientValue('company'), 'text', $isExistingClient ? [] : ['autofocus' => true]); ?>
                            </div>
                            <div class="col-md-4">
                                <?= render_input('phonenumber', 'client_phonenumber', $clientValue('phonenumber')); ?>
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: 2 Fields -->
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-3">
                                <?= render_input('website', 'client_website', $clientValue('website')); ?>
                            </div>
                            <div class="col-md-5">
                                <?php
                                $selected = [];
                                foreach ($customer_groups as $group) $selected[] = $group['groupid'];
                                $groups_html = (is_admin() || get_option('staff_members_create_inline_customer_groups') == '1')
                                    ? render_select_with_input_group('groups_in[]', $groups, ['id', 'name'], 'customer_groups', $selected,
                                        '<div class="input-group-btn"><a href="#" class="btn btn-default" data-toggle="modal" data-target="#customer_group_modal"><i class="fa fa-plus"></i></a></div>',
                                        ['multiple' => true, 'data-actions-box' => true])
                                    : render_select('groups_in[]', $groups, ['id', 'name'], 'customer_groups', $selected, ['multiple' => true, 'data-actions-box' => true]);
                                echo $groups_html;
                                ?>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Address -->
                    <div class="col-md-12">
                        <div class="customer-section customer-section--address">
                            <h4><i class="fa fa-map-marker"></i> Customer Address</h4>
                            <small>Enter the customer's communication address.</small>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="row customer-address-fields">
                            <div class="col-md-5">
                                <?= render_textarea('address', 'client_address', $clientValue('address'), [], [], '', 'height: 92px;'); ?>
                            </div>
                            <div class="col-md-7">
                                <div class="row">
                                    <div class="col-md-6"><?= render_input('city', 'client_city', $clientValue('city')); ?></div>
                                    <div class="col-md-6"><?= render_input('state', 'client_state', $clientValue('state')); ?></div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"><?= render_input('zip', 'Zip Code', $clientValue('zip')); ?></div>
                                    <div class="col-md-6">
                                        <?php
                                        $selected = $clientValue('country', get_option('customer_default_country'));
                                        echo render_select('country', $countries, ['country_id', ['short_name']], 'Country', $selected);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- ====================== GST DETAILS TAB ====================== -->
            <div role="tabpanel" class="tab-pane<?= $this->input->get('tab') == 'gst_details' ? ' active' : ''; ?>" id="gst_details">
                <div class="row">
                    <div class="col-md-12">
                        <div style="background:#f5f8fc; border-left:5px solid #198754; padding:15px 20px; margin-bottom:25px; border-radius:6px;">
                            <h4 style="margin:0;font-weight:600;"><i class="fa fa-file-text"></i> GST Information</h4>
                            <small>Enter GST and tax related details.</small>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <?= render_input('gst_number', 'client_gst_number', $clientValue('gst_number')); ?>
                    </div>
                    <div class="col-md-3">
                        <?php $gst_status = $clientValue('gst_status'); ?>
                        <?= render_select('gst_status', [
                            ['id'=>'','name'=>'Select Status'],
                            ['id'=>'Active','name'=>'Active'],
                            ['id'=>'Inactive','name'=>'Inactive'],
                            ['id'=>'Cancelled','name'=>'Cancelled'],
                            ['id'=>'Suspended','name'=>'Suspended']
                        ], ['id','name'], 'GST Status', $gst_status); ?>
                    </div>
                    <div class="col-md-3">
                        <?php $gst_type = $clientValue('gst_type'); ?>
                        <?= render_select('gst_type', [
                            ['id'=>'','name'=>'Select Type'],
                            ['id'=>'Regular','name'=>'Regular'],
                            ['id'=>'Composition','name'=>'Composition'],
                            ['id'=>'Unregistered','name'=>'Unregistered'],
                            ['id'=>'Consumer','name'=>'Consumer'],
                            ['id'=>'Overseas','name'=>'Overseas'],
                            ['id'=>'SEZ','name'=>'SEZ']
                        ], ['id','name'], 'GST Type', $gst_type); ?>
                    </div>
                    <div class="col-md-3">
                        <?= render_input('gst_state', 'client_gst_state', $clientValue('gst_state')); ?>
                    </div>
                    <div class="col-md-3">
                        <?= render_input('pan_no', 'client_pan_no', $clientValue('pan_no')); ?>
                    </div>
                    <div class="col-md-3">
                        <?= render_input('aadhar_no', 'client_aadhar_no', $clientValue('aadhar_no')); ?>
                    </div>
                </div>
            </div>

            <!-- ====================== BILLING & SHIPPING TAB ====================== -->
            <div role="tabpanel" class="tab-pane<?= $this->input->get('tab') == 'billing_and_shipping' ? ' active' : ''; ?>" id="billing_and_shipping">
                <div class="row">
                    <div class="col-md-12">
                        <div class="row">
                            <!-- Billing Address -->
                            <div class="col-md-6">
                                <h4 class="tw-font-semibold tw-text-base tw-text-black tw-flex tw-justify-between tw-items-center">
                                    <?= _l('billing_address'); ?>
                                    <a href="#" class="billing-same-as-customer tw-text-sm tw-text-black hover:tw-text-neutral-800">
                                        <?= _l('customer_billing_same_as_profile'); ?>
                                    </a>
                                </h4>
                                <?= render_textarea('billing_street', 'billing_street', $clientValue('billing_street')); ?>
                                <?= render_input('billing_city', 'billing_city', $clientValue('billing_city')); ?>
                                <?= render_input('billing_state', 'billing_state', $clientValue('billing_state')); ?>
                                <?= render_input('billing_zip', 'billing_zip', $clientValue('billing_zip')); ?>
                                <?php
                                $selected = $clientValue('billing_country');
                                echo render_select('billing_country', $countries, ['country_id', ['short_name']], 'billing_country', $selected);
                                ?>
                            </div>

                            <!-- Shipping Address -->
                            <div class="col-md-6">
                                <h4 class="tw-font-semibold tw-text-base tw-text-black tw-flex tw-justify-between tw-items-center">
                                    <span><?= _l('shipping_address'); ?></span>
                                    <a href="#" class="customer-copy-billing-address tw-text-sm tw-text-black hover:tw-text-neutral-800">
                                        <?= _l('customer_billing_copy'); ?>
                                    </a>
                                </h4>
                                <?= render_textarea('shipping_street', 'shipping_street', $clientValue('shipping_street')); ?>
                                <?= render_input('shipping_city', 'shipping_city', $clientValue('shipping_city')); ?>
                                <?= render_input('shipping_state', 'shipping_state', $clientValue('shipping_state')); ?>
                                <?= render_input('shipping_zip', 'shipping_zip', $clientValue('shipping_zip')); ?>
                                <?php
                                $selected = $clientValue('shipping_country');
                                echo render_select('shipping_country', $countries, ['country_id', ['short_name']], 'shipping_country', $selected);
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Custom Fields & Customer Admins tabs (if needed) -->
            <?php hooks()->do_action('after_custom_profile_tab_content', $clientObject); ?>

        </div>
    </div>
    <?= form_close(); ?>
</div>

<?php if ($isExistingClient) { /* Customer Admin Modal */ } ?>
 <?php $this->load->view('admin/clients/client_group'); ?>
