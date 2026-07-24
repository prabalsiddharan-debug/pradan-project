<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<nav class="navbar navbar-default header">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                data-target="#theme-navbar-collapse" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand logo logo-text tw-text-2xl tw-font-semibold tw-flex tw-items-center tw-gap-x-2"
                href="<?= site_url(); ?>"
                style="text-decoration: none !important; display: flex !important; align-items: center !important; height: 57px; padding-top: 12px; padding-bottom: 12px;">
                <!-- SVG Logo Icon -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="tw-h-7 tw-w-7" style="color: #ffffff !important; stroke: #ffffff !important; fill: none !important;">
                    <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                    <polyline points="9 22 9 12 15 12 15 22"></polyline>
                </svg>
                <!-- Logo Text -->
                <span style="font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif !important; color: #ffffff !important; display: inline-flex !important; align-items: baseline !important; gap: 4px; margin-left: 8px;">
                    <strong style="font-weight: 800 !important; font-size: 22px !important; letter-spacing: -0.5px !important; text-transform: uppercase !important; color: #ffffff !important;">PRAGATHI</strong>
                    <span style="font-weight: 300 !important; font-size: 16px !important; color: #ffffff !important; letter-spacing: 0px !important;">Home Solutions</span>
                </span>
            </a>
        </div>
        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="theme-navbar-collapse">
            <ul class="nav navbar-nav navbar-right">
                <?php hooks()->do_action('customers_navigation_start'); ?>
                <?php foreach ($menu as $item_id => $item) { ?>
                <li class="customers-nav-item-<?= e($item_id); ?><?= $item['href'] === current_full_url() ? ' active' : ''; ?>"
                    <?= _attributes_to_string($item['li_attributes'] ?? []); ?>>
                    <a href="<?= e($item['href']); ?>"
                        <?= _attributes_to_string($item['href_attributes'] ?? []); ?>>
                        <?php
                     if (! empty($item['icon'])) {
                         echo '<i class="' . $item['icon'] . '"></i> ';
                     }
                    echo e($item['name']);
                    ?>
                    </a>
                </li>
                <?php } ?>
                <?php hooks()->do_action('customers_navigation_end'); ?>
                <?php if (is_client_logged_in()) { ?>
                <li class="dropdown customers-nav-item-profile">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                        aria-expanded="false">
                        <img src="<?= e(contact_profile_image_url($contact->id, 'thumb')); ?>
" data-toggle="tooltip" data-title="<?= e($contact->firstname . ' ' . $contact->lastname); ?>"
                            data-placement="bottom" class="client-profile-image-small">
                    </a>
                    <ul class="dropdown-menu animated fadeIn">
                        <li class="customers-nav-item-edit-profile">
                            <a
                                href="<?= site_url('clients/profile'); ?>">
                                <?= _l('clients_nav_profile'); ?>
                            </a>
                        </li>
                        <?php if ($contact->is_primary == 1) { ?>
                        <?php if (can_loggged_in_user_manage_contacts()) { ?>
                        <li class="customers-nav-item-edit-profile">
                            <a
                                href="<?= site_url('contacts'); ?>">
                                <?= _l('clients_nav_contacts'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="customers-nav-item-company-info">
                            <a
                                href="<?= site_url('clients/company'); ?>">
                                <?= _l('client_company_info'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if (can_logged_in_contact_update_credit_card()) { ?>
                        <li class="customers-nav-item-stripe-card">
                            <a
                                href="<?= site_url('clients/credit_card'); ?>">
                                <?= _l('credit_card'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <?php if (is_gdpr() && get_option('show_gdpr_in_customers_menu') == '1') { ?>
                        <li class="customers-nav-item-announcements">
                            <a
                                href="<?= site_url('clients/gdpr'); ?>">
                                <?= _l('gdpr_short'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="customers-nav-item-announcements">
                            <a
                                href="<?= site_url('clients/announcements'); ?>">
                                <?= _l('announcements'); ?>
                                <?php if ($total_undismissed_announcements != 0) { ?>
                                <span
                                    class="badge"><?= e($total_undismissed_announcements); ?></span>
                                <?php } ?>
                            </a>
                        </li>
                        <?php if (! is_language_disabled()) {
                            ?>
                        <li class="dropdown-submenu pull-left customers-nav-item-languages">
                            <a href="#" tabindex="-1">
                                <?= _l('language'); ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-left">
                                <li class="<?php if (get_contact_language() == '') {
                                    echo 'active';
                                } ?>">
                                    <a
                                        href="<?= site_url('clients/change_language'); ?>">
                                        <?= _l('system_default_string'); ?>
                                    </a>
                                </li>
                                <?php foreach ($this->app->get_available_languages() as $user_lang) { ?>
                                <li <?php if (get_contact_language() == $user_lang) {
                                    echo 'class="active"';
                                } ?>>
                                    <a
                                        href="<?= site_url('clients/change_language/' . $user_lang); ?>">
                                        <?= e(ucfirst($user_lang)); ?>
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                        </li>
                        <?php
                        } ?>
                        <?= hooks()->do_action('customers_navigation_before_logout'); ?>
                        <li class="customers-nav-item-logout">
                            <a
                                href="<?= site_url('authentication/logout'); ?>">
                                <?= _l('clients_nav_logout'); ?>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php } ?>
                <?php hooks()->do_action('customers_navigation_after_profile'); ?>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>