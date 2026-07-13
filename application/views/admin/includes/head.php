<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $isRTL = (is_rtl() ? 'true' : 'false'); ?>

<!DOCTYPE html>
<html lang="<?= e($locale); ?>"
    dir="<?= ($isRTL == 'true') ? 'rtl' : 'ltr' ?>">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <title>
        <?= $title ?? get_option('companyname'); ?>
    </title>

    <?= app_compile_css(); ?>
    
    <!-- Global Table Row Hover Animation Styles -->
    <style>
    /* Fluid animated color transition when cursor moves between rows */
    @keyframes rowHighlight {
      0% { background-color: transparent; }
      50% { background-color: #dbeafe; }
      100% { background-color: #e0f2fe; }
    }

    @keyframes rowFadeOut {
      0% { background-color: #e0f2fe; }
      100% { background-color: transparent; }
    }

    table.dataTable tbody tr {
      transition: 
        background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 0.3s ease-out,
        transform 0.2s ease-out,
        border-left-color 0.3s ease !important;
      position: relative;
      border-left: 3px solid transparent;
    }

    table.dataTable tbody tr td {
      transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1) !important;
    }

    table.dataTable tbody tr:hover {
      background: linear-gradient(90deg, #dbeafe 0%, #e0f2fe 50%, #f0f9ff 100%) !important;
      box-shadow: 
        inset 0 0 0 1px rgba(59, 130, 246, 0.15),
        0 2px 8px rgba(59, 130, 246, 0.08);
      transform: scale(1.001);
      border-left-color: #3b82f6 !important;
      animation: rowHighlight 0.4s ease-out forwards;
    }

    table.dataTable tbody tr:not(:hover) {
      animation: rowFadeOut 0.5s ease-out forwards;
    }

    table.dataTable tbody tr:hover td {
      background-color: transparent !important;
      color: #1e40af;
    }

    table.dataTable tbody tr:hover td a:not(.btn) {
      color: #1d4ed8 !important;
      transition: color 0.3s ease;
    }
    
    /* Also apply to regular tables */
    .table-hover > tbody > tr {
      transition: 
        background-color 0.4s cubic-bezier(0.4, 0, 0.2, 1),
        box-shadow 0.3s ease-out !important;
    }
    
    .table-hover > tbody > tr:hover {
      background: linear-gradient(90deg, #dbeafe 0%, #e0f2fe 50%, #f0f9ff 100%) !important;
    }

    /* ===== FORCE ALL LABELS BLACK ===== */
    label,
    label[class],
    label:not([class]),
    .control-label,
    .form-group label,
    div label,
    form label,
    td label,
    span label,
    html body label {
        color: #000000 !important;
        -webkit-text-fill-color: #000000 !important;
    }
    </style>
    
    <?php render_admin_js_variables(); ?>

    <script>
        var totalUnreadNotifications = <?= e($current_user->total_unread_notifications); ?> ,
            proposalsTemplates = <?= json_encode(get_proposal_templates()); ?> ,
            contractsTemplates = <?= json_encode(get_contract_templates()); ?> ,
            billingAndShippingFields = ['billing_street', 'billing_city', 'billing_state', 'billing_zip',
                'billing_country',
                'shipping_street', 'shipping_city', 'shipping_state', 'shipping_zip', 'shipping_country'
            ],
            isRTL = '<?= e($isRTL); ?>',
            taskid, taskTrackingStatsData, taskAttachmentDropzone, taskCommentAttachmentDropzone, newsFeedDropzone,
            expensePreviewDropzone, taskTrackingChart, cfh_popover_templates = {},
            _table_api;
    </script>
    <?php app_admin_head(); ?>
    <script>
    // Force all label text black - overrides any CSS specificity issue
    function forceLabelsBlack() {
        var labels = document.querySelectorAll('label');
        for (var i = 0; i < labels.length; i++) {
            labels[i].style.setProperty('color', '#000000', 'important');
        }
    }
    // Run on DOM ready and again after a short delay (for dynamically loaded content)
    document.addEventListener('DOMContentLoaded', forceLabelsBlack);
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(forceLabelsBlack, 500);
        setTimeout(forceLabelsBlack, 1500);
    });
    </script>
</head>

<body <?= admin_body_class($bodyclass ?? ''); ?>>
    <?php hooks()->do_action('after_body_start'); ?>