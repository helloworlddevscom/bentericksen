<template>
    <div id="main_navigation_wrap">
        <nav class="navbar navbar-inverse" role="navigation">
            <div class="container">
                <div class="row">
                    <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#admin_navigation">
                            <span class="sr-only">Toggle navigation</span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                        </button>
                    </div>
                    <div class="collapse navbar-collapse center-block" id="admin_navigation">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-suitcase navigation_img"></i>BUSINESS LIST</a>
                                <ul class="dropdown-menu">
                                    <li><Link href="/admin/business">BUSINESS LIST</Link>
                                    <li><a href="/admin/business/create">NEW BUSINESS SETUP</a></li>
                                </ul>
                            </li>
                            <li><Link href="/admin/login-list"><i class="fa fa-users navigation_img"></i>LOGIN LIST</Link></li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-copy navigation_img"></i>POLICIES</a>
                                <ul class="dropdown-menu">
                                    <li><a href="/admin/policies">POLICY LIST</a></li>
                                    <li><a href="/admin/policies/updates">POLICY UPDATE LIST</a></li>
                                    <li><a href="/admin/policies/create">NEW POLICY</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-comment-o navigation_img"></i>JOB DESCRIPTIONS</a>
                                <ul class="dropdown-menu">
                                    <li><a href="/admin/job-descriptions">JOB DESCRIPTIONS</a></li>
                                    <li><a href="/admin/job-descriptions/create">NEW JOB DESCRIPTION</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-file-text-o navigation_img"></i>FORMS</a>
                                <ul class="dropdown-menu">
                                    <li><a href="/admin/forms">FORMS</a></li>
                                    <li><a href="/admin/forms/create">NEW FORM</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-list-alt navigation_img"></i>FAQ'S</a>
                                <ul class="dropdown-menu">
                                    <li><a href="/admin/faqs">FAQ'S</a></li>
                                    <li><a href="/admin/faqs/create">ADD NEW FAQ</a></li>
                                </ul>
                            </li>
                            <li><Link href="/admin/email-list"><i class="fa fa-envelope navigation_img"></i>Email Log</Link></li>
                            <li>
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-question-circle navigation_img"></i>HELP</a>
                                <ul class="dropdown-menu">
                                    <li><a href="/admin/help">HELP</a></li>
                                    <li><a href="/admin/help/create">NEW HELP</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</template>
<script>
import { Link } from '@inertiajs/inertia-vue'

export default {
    components: {
        Link
    }
}
</script>
