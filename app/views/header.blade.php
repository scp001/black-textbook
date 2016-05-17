<header class="banner navbar navbar-static-top" role="banner">
    <div class="container">

        <div class="navbar-header">

            <div class="navbar-brand">
                <a title="KnowledgePress" href="{{URL::to('')}}">
                    <!--
                    <img src="{{asset('logo.png')}}" alt="KnowledgePress"/>
                    -->
                    <h1>
                        <span style="color:#0d9fef;"> BOOK </span>Exchange
                    </h1>
                </a>
            </div>

            <button data-target=".nav-responsive" data-toggle="collapse" type="button" class="navbar-toggle">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

        </div>

        <nav class="nav-main hidden-xs" role="navigation">
            <ul id="menu-main" class="nav navbar-nav">
                <li><a href="{{URL::to('')}}">Home</a></li>
                @if (Auth::check())
                <li class="dropdown">
                <a class="dropdown-toggle" data-target="#" href="{{URL::to('hoppy/myhoppies')}}">Hobbies</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="" href="{{URL::to('hoppy/myhoppies')}}">My Hobbies</a></li>
                                        <li><a class="" href="{{URL::to('hoppy/search')}}">Search Hobbies</a></li>
                                    </ul>
                                </li>
                <li class="dropdown"><a class="dropdown-toggle" data-target="#" href="{{URL::to('mybooks')}}">Books</a>
                                    <ul class="dropdown-menu">
                                        <li><a class="" href="{{URL::to('mybooks')}}">My Books</a></li>
                                        <li><a class="" href="{{URL::to('books/search')}}">Search Books</a></li>
                                    </ul>
                                </li>
                        <li><a class="" href="{{URL::to('inbox')}}">My Inbox</a></li>
                <li class="dropdown"><a class="dropdown-toggle" data-target="#" href="{{URL::to('myprofile')}}">{{Auth::user()->username}}</a>
                    <ul class="dropdown-menu">

                        <li><a class="" href="{{URL::to('myprofile')}}">My profile</a></li>
                        <li><a class="" href="{{URL::to('users/logout')}}">Logout</a></li>
                    </ul>
                </li>
                @else
                <li><a href="{{URL::to('login')}}">Login</a></li>
                <li><a href="{{URL::to('signup')}}" class="btn btn-success headerbtn">Sign up</a></li>
                @endif
                <!-- <li class="dropdown"><a class="dropdown-toggle" data-target="#" href="index.html#">Features</a>
                    <ul class="dropdown-menu">
                        <li><a href="http://demo3.pressapps.co/html/knowledgepress2/contact.html">Contact Page</a></li>
                        <li><a href="http://demo3.pressapps.co/html/knowledgepress2/full.html">Full Width Page</a></li>
                        <li><a href="http://demo3.pressapps.co/html/knowledgepress2/components.html">B3 Components</a></li>
                        <li><a href="http://demo3.pressapps.co/html/knowledgepress2/columns.html">Columns</a></li>
                        <li><a href="http://demo3.pressapps.co/html/knowledgepress2/typography.html">Typography</a></li>
                        <li><a href="http://demo3.pressapps.co/html/knowledgepress2/videos.html">Responsive Videos</a></li>
                    </ul>
                </li>-->
            </ul>
        </nav>

        <!-- Responsive Nav -->
        <div class="visible-xs">
            <nav class="nav-responsive collapse" role="navigation">
                <ul class="nav">
                    <li><a href="http://demo3.pressapps.co/html/knowledgepress2/index.html">Home</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/index2.html">Home 2</a></li>
                    <li><a href="http://demo3.pressapps.co/html/knowledgepress2/knowledgebase.html">Knowledge Base</a></li>
                    <li><a href="http://demo3.pressapps.co/html/knowledgepress2/articles.html">Articles</a></li>
                    <li><a href="http://demo3.pressapps.co/html/knowledgepress2/faq-accordion.html">FAQ</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/faq-accordion.html">FAQ Accordion</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/faq.html">FAQ Default</a></li>
                    <li><a href="index.html#">Features</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/contact.html">Contact Page</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/full.html">Full Width Page</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/components.html">B3 Components</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/columns.html">Columns</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/typography.html">Typography</a></li>
                    <li><a class="responsive-submenu" href="http://demo3.pressapps.co/html/knowledgepress2/videos.html">Responsive Videos</a></li>
                </ul>
            </nav>
        </div>

    </div>

</header>
