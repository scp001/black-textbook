<footer>
    <div class="footer-widgets">
        <div class="container">
            <div class="row">

                <section class="col-sm-3">
                    <div>
                        <h3>Recent Books</h3>
                        <ul>
                        <?php
                        $recentBooks = DB::table('books')->where('id','>',0)->orderBy('created_at','DESC')->limit(8)->remember(100)->get(['title','id']);
                        ?>
                        @foreach($recentBooks as $recentBook )

                            <li>
                            <i class="fa fa-fw fa-file-text-o"></i>
                            <a href="{{URL::to('books/show/'.$recentBook->id)}}" rel="bookmark" title="{{$recentBook->title}}">{{$recentBook->title}}</a>
                            </li>

                        @endforeach
                        </ul>
                    </div>
                </section>

                <section class="col-sm-3">
                    <div>
                        <h3>Recent Users</h3>
                        <ul>
                            <?php
                                $recentUsers = DB::table('users')->where('id','>',0)->orderBy('created_at','DESC')->limit(8)->remember(200)->get(['username','id']);
                                ?>
                                @foreach($recentUsers as $recentUser )
                                    <li>
                                    <i class="fa fa-fw fa-user"></i>
                                    <a href="{{URL::to('myprofile?pid='.$recentUser->id)}}" rel="bookmark" title="{{$recentUser->username}}">{{$recentUser->username}}</a>
                                    </li>

                                @endforeach
                                </ul>
                    </div>
                </section>

                <section class="col-sm-3">
                    <div>
                        <h3>Top hobbies</h3>
                        <?php
                        $topHobbies = DB::table('user_hoppies as uh')->join('hoppies as h','h.id','=','uh.hoppy_id')->select(DB::raw('COUNT(uh.hoppy_id ) as count ,h.title '))->groupBy('uh.hoppy_id')->orderBy('count','DESC')->limit(8)->remember(400)->get();

                        ?>
                        <ul>
                        @foreach($topHobbies as $hoppy)
                            <li>
                                <i class="fa fa-fw fa-file-text-o"></i>
                                 <a href="{{URL::to('hoppy/search?q='.$hoppy->title)}}" rel="bookmark" title="{{$hoppy->title}}">{{$hoppy->title}} ({{$hoppy->count}})</a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </section>

                <section class="col-sm-3">
                    <div>
                        <h3>Popular Universities</h3>
                        <?php
$topUniversities = DB::table('user_meta')->select(DB::raw('count(id) as count,meta_value as uni'))->where('meta_key','school')->groupBy('meta_value')->orderBy('count','DESC')->limit(8)->remember(600)->get();
                        ?>
                        <ul>
                        @foreach($topUniversities as $uni)
                            <li>
                                <i class="fa fa-fw fa-file-text-o"></i> {{$uni->uni}} ( {{$uni->count}} )
                            </li>
                        @endforeach
                        </ul>
                    </div>
                </section>

            </div>
        </div>
    </div>

    <!-- Footer Info -->
    <div class="footer-info">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="social-icons">
                        <li class="btn-social btn-twitter">
                            <a href="http://twitter.com/username"><img src="{{asset('assets/img/icons_twitter.png')}}" alt="Twitter" />
                            </a>
                        </li>
                        <li class="btn-social btn-facebook">
                            <a href="http://facebook.com/username"><img src="{{asset('assets/img/icons_facebook.png')}}" alt="Facebook" />
                            </a>
                        </li>
                        <li class="btn-social btn-google">
                            <a href="http://gplus.to/username"><img src="{{asset('assets/img/icons_google.png')}}" alt="Google+" />
                            </a>
                        </li>
                        <li class="btn-social btn-linkedin">
                            <a href="http://linkedin.com/in/username"><img src="{{asset('assets/img/icons_linkedin.png')}}" alt="LinkedIn" />
                            </a>
                        </li>
                        <li class="btn-social btn-vimeo">
                            <a href="http://vimeo.com/username"><img src="{{asset('assets/img/icons_vimeo.png')}}" alt="Vimeo" />
                            </a>
                        </li>
                        <li class="btn-social btn-youtube">
                            <a href="http://youtube.com/username"><img src="{{asset('assets/img/icons_youtube.png')}}" alt="YouTube" />
                            </a>
                        </li>
                        <li class="btn-social btn-flickr">
                            <a href="http://flickr.com/photos/username"><img src="{{asset('assets/img/icons_flickr.png')}}" alt="Flickr" />
                            </a>
                        </li>
                        <li class="btn-social btn-dribbble">
                            <a href="http://dribbble.com/username"><img src="{{asset('assets/img/icons_dribbble.png')}}" alt="Dribbble" />
                            </a>
                        </li>
                        <li class="btn-social btn-rss">
                            <a href="http://website.com/rss"><img src="{{asset('assets/img/icons_rss.png')}}" alt="RSS" />
                            </a>
                        </li>
                    </ul>
                    <p class="copyright">Copyright 2012. Powered by <a href='{{URL::to('')}}'>BookExchange Ltd.</a></p>
                </div>
            </div>
        </div>
    </div>

</footer>