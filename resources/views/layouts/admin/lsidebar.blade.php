@php
/**
 * @author		Giacomo Piva <gpiva@innovativa.it>
 * @category	Layout file
 * @version    	1.0
 * @see        	https://github.com/gurayyarar/AdminBSBMaterialDesign
 * @see			https://gurayyarar.github.io/AdminBSBMaterialDesign/index.html
 * @see 		https://getbootstrap.com/docs/3.3/
 */	
@endphp

<!-- Left Sidebar -->
<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src="/images/user.png" width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Ciao <span class="name">{{ Auth::user()->name }}</span>! </div>
            <div class="email">Hai effettuato il login come: <span class="name">{{ Auth::user()->name }} </span><br/> 
            Il tuo ruolo è: 
                <span class="role" id="role">{{ Auth::user()->roleName() }}</span>
            </div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <!--li><a href="javascript:void(0);"><i class="material-icons">person</i>Profile</a></li-->
                    <!--li role="separator" class="divider"></li-->
                    <!--li><a href="javascript:void(0);"><i class="material-icons">group</i>Followers</a></li-->
                    <!--li role="separator" class="divider"></li-->
                    <li><a href="{{ url('/logout') }}"><i class="material-icons">input</i>Sign Out</a></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu">
        <ul class="list">
            <li class="header">MENU PRINCIPALE</li>
            @role(['admin'])
            <li class="{{ is_section_active(['home']) ? 'active' : '' }}">
                <a href="{{ route('admin.home') }}">
                    <i class="material-icons">home</i>
                    <span>Home</span>
                </a>
            </li>
            <li class="{{ is_section_active(['utenti']) ? 'active' : '' }}">
                <a href="{{ route('admin.user.index') }}">
                    <i class="material-icons">account_circle</i>
                    <span>Utenti</span>
                </a>
            </li>
            @endrole

            <!--li>
                <a href="javascript:void(0);" class="menu-toggle">
                    <i class="material-icons">widgets</i>
                    <span>Widgets</span>
                </a>
                <ul class="ml-menu">
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <span>Cards</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/widgets/cards/basic.html">Basic</a>
                            </li>
                            <li>
                                <a href="pages/widgets/cards/colored.html">Colored</a>
                            </li>
                            <li>
                                <a href="pages/widgets/cards/no-header.html">No Header</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="javascript:void(0);" class="menu-toggle">
                            <span>Infobox</span>
                        </a>
                        <ul class="ml-menu">
                            <li>
                                <a href="pages/widgets/infobox/infobox-1.html">Infobox-1</a>
                            </li>
                            <li>
                                <a href="pages/widgets/infobox/infobox-2.html">Infobox-2</a>
                            </li>
                            <li>
                                <a href="pages/widgets/infobox/infobox-3.html">Infobox-3</a>
                            </li>
                            <li>
                                <a href="pages/widgets/infobox/infobox-4.html">Infobox-4</a>
                            </li>
                            <li>
                                <a href="pages/widgets/infobox/infobox-5.html">Infobox-5</a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li-->

            <!--li class="header">LABELS</li>
            <li>
                <a href="javascript:void(0);">
                    <i class="material-icons col-light-blue">donut_large</i>
                    <span>Information</span>
                </a>
            </li-->
        </ul>
    </div>
    <!-- #Menu -->

    <x-admin.footer />

</aside>
<!-- #END# Left Sidebar -->
