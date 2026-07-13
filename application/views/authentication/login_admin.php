<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php $this->load->view('authentication/includes/head.php'); ?>

<body class="login_admin">

    <div class="tw-max-w-md tw-mx-auto tw-pt-4 authentication-form-wrapper tw-relative tw-z-20">
        <div class="company-logo text-center" style="padding: 10px;">
            <div style="max-height: 100px; overflow: hidden; display: flex; justify-content: center;">
                <?php get_dark_company_logo(); ?>
            </div>
        </div>

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