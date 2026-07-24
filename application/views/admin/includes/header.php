<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div id="header">
    <button type="button"
        class="hide-menu tw-inline-flex tw-bg-transparent tw-border-0 tw-p-1 tw-mt-4 hover:tw-bg-neutral-600/10 tw-text-neutral-600 hover:tw-text-neutral-800 focus:tw-text-neutral-800 focus:tw-outline-none tw-rounded-md tw-mx-4 ltr:md:tw-ml-4 rtl:md:tw-mr-4 ltr:tw-float-left  rtl:tw-float-right invisible pointer-events-none ">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" class="tw-h-4 tw-w-4 tw-text-current">
            <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                d="M2.25 18.003h19.5m-19.5-6h19.5m-19.5-6h19.5"></path>
        </svg>
    </button>
    <nav>
        <div class="tw-flex tw-justify-between">
            <div class="tw-overflow-hidden tw-shrink-0">
                <div id="logo"
                    class="tw-h-[57px] tw-hidden md:tw-flex tw-items-center [&_img]:tw-h-9 [&_img]:tw-w-auto">
                    <a class="logo logo-text tw-text-2xl tw-font-semibold tw-flex tw-items-center tw-gap-x-2"
                        href="<?= hooks()->apply_filters('admin_header_logo_href', admin_url()); ?>"
                        style="text-decoration: none !important; display: flex !important; align-items: center !important;">
                        <!-- SVG Logo Icon -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="tw-h-7 tw-w-7" style="color: #ffffff !important; stroke: #ffffff !important; fill: none !important;">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <!-- Logo Text -->
                        <span style="font-family: 'Inter', 'Helvetica Neue', Helvetica, Arial, sans-serif !important; color: #ffffff !important; display: inline-flex !important; align-items: baseline !important; gap: 4px;">
                            <strong style="font-weight: 800 !important; font-size: 22px !important; letter-spacing: -0.5px !important; text-transform: uppercase !important; color: #ffffff !important;">PRAGATHI</strong>
                            <span style="font-weight: 300 !important; font-size: 16px !important; color: #ffffff !important; letter-spacing: 0px !important;">Home Solutions</span>
                        </span>
                    </a>
                </div>
            </div>
            
            <div class="mobile-menu tw-shrink-0 ltr:tw-ml-4 rtl:tw-mr-4">
                <button type="button"
                    class="navbar-toggle visible-md visible-sm visible-xs mobile-menu-toggle collapsed tw-ml-1.5 tw-text-neutral-600 hover:tw-text-neutral-800"
                    data-toggle="collapse" data-target="#mobile-collapse" aria-expanded="false">
                    <i class="fa fa-chevron-down fa-lg"></i>
                </button>
                <ul class="mobile-icon-menu tw-inline-flex tw-mt-5">
                    <?php
               // To prevent not loading the timers twice
            if (is_mobile()) { ?>
                    <li class="dropdown notifications-wrapper header-notifications tw-block ltr:tw-mr-3 rtl:tw-ml-3">
                        <?php $this->load->view('admin/includes/notifications'); ?>
                    </li>
                    <li class="header-timers ltr:tw-mr-1.5 rtl:tw-ml-1.5">
                        <a href="#" id="top-timers" class="dropdown-toggle top-timers tw-block tw-h-5 tw-w-5"
                            data-toggle="dropdown">
                            <i
                                class="fa-regular fa-clock fa-lg tw-text-neutral-500 group-hover:tw-text-neutral-800 tw-shrink-0<?= count($startedTimers) > 0 ? ' tw-animate-spin-slow' : ''; ?>"></i>
                            <span
                                class="tw-leading-none tw-px-1 tw-py-0.5 tw-text-xs bg-success tw-z-10 tw-absolute tw-rounded-full -tw-right-3 -tw-top-2 tw-min-w-[18px] tw-min-h-[18px] tw-inline-flex tw-items-center tw-justify-center icon-started-timers<?= $totalTimers = count($startedTimers) == 0 ? ' hide' : ''; ?>"><?= count($startedTimers); ?></span>
                        </a>
                        <ul class="dropdown-menu animated fadeIn started-timers-top width300" id="started-timers-top">
                            <?php $this->load->view('admin/tasks/started_timers', ['startedTimers' => $startedTimers]); ?>
                        </ul>
                    </li>
                    <?php } ?>
                </ul>
                <div class="mobile-navbar collapse" id="mobile-collapse" aria-expanded="false" style="height: 0px;"
                    role="navigation">
                    <ul class="nav navbar-nav">
                        <li class="header-my-profile"><a
                                href="<?= admin_url('profile'); ?>">
                                <?= _l('nav_my_profile'); ?>
                            </a>
                        </li>
                        <li class="header-my-timesheets"><a
                                href="<?= admin_url('staff/timesheets'); ?>">
                                <?= _l('my_timesheets'); ?>
                            </a>
                        </li>
                        <li class="header-edit-profile"><a
                                href="<?= admin_url('staff/edit_profile'); ?>">
                                <?= _l('nav_edit_profile'); ?>
                            </a>
                        </li>
                        <?php if (is_staff_member()) { ?>
                        <li class="header-newsfeed">
                            <a href="#" class="open_newsfeed mobile">
                                <?= _l('whats_on_your_mind'); ?>
                            </a>
                        </li>
                        <?php } ?>
                        <li class="header-logout">
                            <a href="#" onclick="logout(); return false;">
                                <?= _l('nav_logout'); ?>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="nav navbar-nav navbar-right -tw-mt-px">
                <?php do_action_deprecated('after_render_top_search', [], '3.0.0', 'admin_navbar_start'); ?>
                <?php hooks()->do_action('admin_navbar_start'); ?>
                
                <!-- Logged In User Name -->
                <li class="tw-flex tw-items-center">
                    <span class="tw-text-white tw-font-semibold hidden-xs" style="color: #ffffff !important; font-size: 14px; margin-right: 8px;">
                        <?= e(get_staff_full_name()); ?>
                    </span>
                </li>

                <!-- Quick Actions / Quick Create Button -->
                <?php $quickActions = collect($this->app->get_quick_actions_links())->reject(function ($action) {
                    return isset($action['permission']) && staff_cant('create', $action['permission']);
                }); ?>
                <?php if ($quickActions->isNotEmpty()) { ?>
                <li class="icon header-quick-create tw-relative"
                    title="<?= _l('quick_create'); ?>"
                    data-toggle="tooltip" data-placement="bottom">
                    <a href="#" class="header-quick-create-btn" data-toggle="dropdown">
                        <i class="fa-regular fa-plus"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right animated fadeIn tw-text-base">
                        <li class="dropdown-header tw-mb-1">
                            <?= _l('quick_create'); ?>
                        </li>
                        <?php foreach ($quickActions as $key => $item) {
                            $url = '';
                            if (isset($item['permission'])) {
                                if (staff_cant('create', $item['permission'])) {
                                    continue;
                                }
                            }
                            if (isset($item['custom_url'])) {
                                $url = $item['url'];
                            } else {
                                $url = admin_url('' . $item['url']);
                            }
                            $href_attributes = '';
                            if (isset($item['href_attributes'])) {
                                foreach ($item['href_attributes'] as $key => $val) {
                                    $href_attributes .= $key . '="' . $val . '"';
                                }
                            } ?>
                        <li>
                            <a href="<?= e($url); ?>"
                                <?= $href_attributes; ?>
                                class="tw-group tw-inline-flex tw-space-x-0.5 tw-text-neutral-700">
                                <?php if (isset($item['icon'])) { ?>
                                <i
                                    class="<?= e($item['icon']); ?> tw-text-neutral-400 group-hover:tw-text-neutral-600 tw-h-5 tw-w-5"></i>
                                <?php } ?>
                                <span>
                                    <?= e($item['name']); ?>
                                </span>
                            </a>
                        </li>
                        <?php
                        } ?>
                    </ul>
                </li>
                <?php } ?>

                <!-- Clock / Timers -->
                <li class="icon header-timers timer-button tw-relative"
                    data-placement="bottom" data-toggle="tooltip"
                    data-title="<?= _l('my_timesheets'); ?>">
                    <a href="#" id="top-timers" class="top-timers header-action-btn tw-group" data-toggle="dropdown">
                        <i class="fa-regular fa-clock tw-shrink-0<?= count($startedTimers) > 0 ? ' tw-animate-spin-slow' : ''; ?>"></i>
                        <span
                            class="tw-leading-none tw-px-1 tw-py-0.5 tw-text-xs bg-success tw-z-10 tw-absolute tw-rounded-full -tw-right-1.5 tw-top-2 tw-min-w-[18px] tw-min-h-[18px] tw-inline-flex tw-items-center tw-justify-center icon-started-timers<?= $totalTimers = count($startedTimers) == 0 ? ' hide' : ''; ?>">
                            <?= count($startedTimers); ?>
                        </span>
                    </a>
                    <ul class="dropdown-menu animated fadeIn started-timers-top width300" id="started-timers-top">
                        <?php $this->load->view('admin/tasks/started_timers', ['startedTimers' => $startedTimers]); ?>
                    </ul>
                </li>

                <!-- Notifications -->
                <li class="icon dropdown tw-relative tw-block notifications-wrapper header-notifications"
                    data-toggle="tooltip"
                    title="<?= _l('nav_notifications'); ?>"
                    data-placement="bottom">
                    <?php $this->load->view('admin/includes/notifications'); ?>
                </li>

                <!-- Logout -->
                <li class="icon header-logout"
                    data-placement="bottom" data-toggle="tooltip"
                    title="<?= _l('nav_logout'); ?>">
                    <a href="#" onclick="logout(); return false;" class="header-action-btn">
                        <i class="fa fa-power-off"></i>
                    </a>
                </li>

                <?php hooks()->do_action('admin_navbar_end'); ?>
            </ul>
        </div>
    </nav>
</div>