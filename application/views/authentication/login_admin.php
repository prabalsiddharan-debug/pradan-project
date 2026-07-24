<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="login_admin">

    <div id="header" style="position: fixed !important; top: 0 !important; left: 0 !important; width: 100% !important; z-index: 1005 !important;">
        <nav>
            <div class="tw-flex tw-justify-between">
                <div class="tw-overflow-hidden tw-shrink-0">
                    <div id="logo" class="tw-h-[57px] tw-flex tw-items-center [&_img]:tw-h-9 [&_img]:tw-w-auto tw-ml-4" style="display: flex !important; align-items: center !important;">
                        <a class="logo logo-text tw-text-2xl tw-font-semibold tw-flex tw-items-center tw-gap-x-2"
                            href="#"
                            style="text-decoration: none !important; display: flex !important; align-items: center !important;">
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
                </div>
            </div>
        </nav>
    </div>

    <div class="tw-max-w-md tw-mx-auto tw-pt-4 authentication-form-wrapper tw-relative tw-z-20">

        <div class="text-center tw-mb-1">
            <h1 class="tw-text-neutral-800 tw-text-lg tw-font-bold tw-mb-0" style="background:none !important; color:#000 !important; padding:2px 0 !important;">
                <?= _l('admin_auth_login_heading'); ?>
            </h1>
            <p class="tw-text-neutral-600" style="margin:2px 0;">
                <?= _l('welcome_back_sign_in'); ?>
            </p>
        </div>

        <div
            class="tw-bg-white tw-mx-2 sm:tw-mx-6 tw-py-4 tw-px-6 sm:tw-px-8 tw-shadow-sm tw-rounded-lg tw-border tw-border-solid tw-border-neutral-600/20">

            <?php $this->load->view('authentication/includes/alerts'); ?>

            <?= form_open($this->uri->uri_string()); ?>

            <?= validation_errors('<div class="alert alert-danger text-center">', '</div>'); ?>

            <?php hooks()->do_action('after_admin_login_form_start'); ?>

            <div class="form-group">
                <label for="email" class="control-label !tw-mb-3">
                    <?= _l('admin_auth_login_email'); ?>
                </label>
                <input type="email" id="email" name="email" class="form-control" autofocus="1">
            </div>

            <div class="form-group tw-mt-3">
                <span class="tw-inline-flex tw-justify-between tw-items-end tw-w-full tw-mb-3">
                    <label for="password" class="control-label !tw-m-0">
                        <?= _l('admin_auth_login_password'); ?>
                    </label>
                    <a href="<?= admin_url('authentication/forgot_password'); ?>"
                        class="text-muted">
                        <?= _l('admin_auth_login_fp'); ?>
                    </a>
                </span>

                <input type="password" id="password" name="password" class="form-control">
            </div>

            <?php if (show_recaptcha()) { ?>
            <div class="g-recaptcha tw-mb-4"
                data-sitekey="<?= get_option('recaptcha_site_key'); ?>">
            </div>
            <?php } ?>

            <div class="form-group">
                <div class="checkbox checkbox-inline">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">
                        <?= _l('admin_auth_login_remember_me'); ?></label>
                </div>
            </div>

            <div class="tw-mt-3">
                <button type="submit" class="btn btn-primary btn-block tw-font-semibold tw-py-2">
                    <?= _l('admin_auth_login_button'); ?>
                </button>
            </div>

            <?php hooks()->do_action('before_admin_login_form_close'); ?>

            <?= form_close(); ?>
        </div>
    </div>

</body>

</html>