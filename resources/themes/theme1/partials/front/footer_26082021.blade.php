 
@if(config('settings.footer_enable') == '1') 
<div class="section sparkle-advantage">
     <div class="container">
       <div class="heading-sec">
         <h2>JewelNidhi Sparkling Advantages </h2>
         <img src="{{ asset('img/home_line.png') }}" alt=""/>
       </div>
       <div class="row">
         <div class="col-lg-8 offset-lg-2">
           <div class="row">
             
               <div class="box">
                 <img src="{{ asset('img/free_shipping.jpg') }}" alt=""/>
                 <span>Free Shipping </br> Insurance</span>
               </div>
            
             
              <div class="box">
                <img src="{{ asset('img/lifetime_exchange.jpg') }}" alt=""/>
                <span>Lifetime Exchange </br>Buyback</span>
              </div>
            
            
              <div class="box">
                <img src="{{ asset('img/reffund.jpg') }}" alt=""/>
                <span>100% Refund</span>
              </div>
           
            
              <div class="box">
                <img src="{{ asset('img/certified_jewel.jpg') }}" alt=""/>
                <span>100% Certified </br>Jewellery</span>
              </div>
            
           </div>
         </div>
       </div>
     </div>
   </div>





<footer class="section">
    <div class="container">
      <div class="row">
        <div class="col-xl-3 col-lg-4 col-md-4">
        
          <div class="f-logo">
          @if(config('settings.site_logo_enable') == 1)
            <a href="{{ url('/') }}">
              <img src="{{ URL::asset('img/logo_new.gif') }}" alt="{{ config('settings.site_logo_name') }}" height="{{ config('settings.site_logo_height') }}" />
            </a>
          @endif
          </div>
        
        </div>
         
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
          <div class="f-menu">
            <h5>Company</h5>
            <ul>
              @if(isset($companypage))
                  @foreach($companypage as $value)
                  <li>
                  <a href="{{ route('front.page.show',$value->slug) }}">{{ isset($value->title) ? ucwords($value->title) : '' }}</a>
                  </li>
                  @endforeach
              @endif
            </ul>
          </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
          <div class="f-menu">
            <h5>JewelNidhi</h5>
            <ul>
              @if(isset($jewelnidhipage))
                  @foreach($jewelnidhipage as $value)
                  
                  <li>
                  @if($value->slug == 'lifetime-exchange' || $value->slug == 'lifetime-buy-back') 
                    <a href="{{ url('/') }}/return-policy#recommendSection">{{ isset($value->title) ? ucwords($value->title) : '' }}</a>
                  @elseif($value->slug == '15-day-returns')
                    <a href="{{ url('/') }}/return-policy#policyTitle" id="policyID">{{ isset($value->title) ? ucwords($value->title) : '' }}</a>
                  @else
                    <a href="{{ route('front.page.show',$value->slug) }}">{{ isset($value->title) ? ucwords($value->title) : '' }}</a>
                  @endif
                  
                  </li>
                  @endforeach
              @endif
            </ul>
          </div>
        </div>
        <div class="col-xl-2 col-lg-4 col-md-4 col-6">
          <div class="f-menu">
            <h5>Customer care</h5>
            <ul>
              @if(isset($customerpage))
                  @foreach($customerpage as $value)
                  <li>
                  <a href="{{ route('front.page.show',$value->slug) }}">{{ isset($value->title) ? ucwords($value->title) : '' }}</a>
                  </li>
                  @endforeach
              @endif
            </ul>
          </div>
        </div>
        <div class="col-xl-3 col-lg-4 col-md-4 col-6">
          <div class="f-menu">
            <h5>Contact  Us  </h5>
            <ul>
            @if(config('settings.contact_email'))
              <li><a href="mailto: {{ config('settings.contact_email') }}">{{ config('settings.contact_email') }}</a></li>
            @endif
            @if(config('settings.contact_number'))
              <li>{{ config('settings.contact_number') }}</li>
            @endif
            
              <li><a href="{{ url('/') }}">www.jewelnidhi.com</a></li>
            
            </ul>
          </br>
            <h5 class="follow-head">Follow us:  </h5>
            <ul class="social-icon">
             
              @if(config('settings.social_link_instagram_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_instagram') }}"><img src="{{ URL::asset('img/insta-icon.png') }} " alt=""/></a></li>
              @endif
              @if(config('settings.social_link_facebook_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_facebook') }}"><img src="{{ URL::asset('img/facebook.png') }}" alt=""/></a></li>
              @endif
              @if(config('settings.social_link_twitter_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_twitter') }}"><img src="{{ URL::asset('img/twitter.png') }} " alt=""/></a></li>
              @endif
              @if(config('settings.social_link_youtube_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_youtube') }}"><img src="{{ URL::asset('img/youtube.png') }}" alt=""/></a></li>
              @endif
              
              @if(config('settings.social_link_pintrest_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_pintrest') }}"><i class="fa fa-pinterest-p"></i></a></li>
              @endif
              @if(config('settings.social_link_whatsapp_enable') == '1')
                <li><a target="_blank" href="#"><i class="fa fa-whatsapp"></i></a></li>
              @endif
            </ul>
          </div>
        </div>
		<div class="col-12 mobile-follow">
          <div class="f-menu">
            <h5>Follow us:  </h5>
            <ul class="social-icon">
             
              @if(config('settings.social_link_instagram_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_instagram') }}"><img src="{{ URL::asset('img/insta-icon.png') }} " alt=""/></a></li>
              @endif
              @if(config('settings.social_link_facebook_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_facebook') }}"><img src="{{ URL::asset('img/facebook.png') }}" alt=""/></a></li>
              @endif
              @if(config('settings.social_link_twitter_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_twitter') }}"><img src="{{ URL::asset('img/twitter.png') }} " alt=""/></a></li>
              @endif
              @if(config('settings.social_link_youtube_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_youtube') }}"><img src="{{ URL::asset('img/youtube.png') }}" alt=""/></a></li>
              @endif
              
              @if(config('settings.social_link_pintrest_enable') == '1')
                <li><a target="_blank" href="{{ config('settings.social_link_pintrest') }}"><i class="fa fa-pinterest-p"></i></a></li>
              @endif
              @if(config('settings.social_link_whatsapp_enable') == '1')
                <li><a target="_blank" href="#"><i class="fa fa-whatsapp"></i></a></li>
              @endif
            </ul>
          </div>
        </div>
      </div>
    </div>
    
    <div class="copyright">
      <div class="container">
        <div>
          <p> Copyright JewelNidhi@2020  <a href="{{ route('front.page.show','return-policy') }}">Return policy</a>  <a href="{{ route('front.page.show','privacy-policy') }}"> Privacy policy</a> <a href="{{ route('front.page.show','term-conditions') }}">Terms & conditions</a> </p>
        
        <ul>
          <li><i class="fa fa-cc-mastercard"></i></li>
          <li><i class="fa fa-cc-discover"></i></li>
          <li><i class="fa fa-cc-visa"></i></li>
          <li><i class="fa fa-cc-amex"></i></li>
          <li><i class="fa fa-cc-paypal"></i></li>
        </ul>
        <div class="logistic-dept">
            <span> Logistics partner</span>
            <img src="{{ asset('img/abc.jpeg') }}" alt="">
        </div>
      </div>
      </div>
    </div>
    
  </footer>
@endif
     
    <script src="{{ asset('js/slick.js')}}"></script>
    <script src="https://unpkg.com/popper.js@1.15.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script src="{{ asset('js/aos.js')}}"></script>
    <script src="{{ asset('js/jquery.responsive-tables.js')}}"></script>
 
    
 
    <script>

          
        $(document).ready(function() {

          $.responsiveTables();

          $(".pro_qty").on("change",function() {
            var tempid = $(this).attr('id');
            var id = tempid.replace("pro_qty_","");
            
            $("#qty_"+id).val($(this).val());
          });
          
          $(".closeLogin").on("click",function() {

            $("#loginform").find("input[type=text],input[type=password]").val("");
            
          });

          $(".closeRegister").on("click",function() {

            $('#customCheck').prop('checked', false);
            $("#registerform").find("input[type=text],input[type=password],input[type=email],input[type=hidden]").val("");
             
          });
          
            
        });
        
        $(document).ready(function () {
            AOS.init({
                duration: 500,
            })
        })
    
        var slideNumber = 1;
        setInterval(function(){
          $(".scroll-rate-sec .inactive." + slideNumber + "_slider").removeClass("inactive").siblings().addClass("inactive");
          slideNumber++;
          if(slideNumber == 4){
            slideNumber = 1;
          }
          },2000);;

</script>

<script>
      //Get the button
      var mybutton = document.getElementById("myBtn");
      
      // When the user scrolls down 20px from the top of the document, show the button
      window.onscroll = function() {scrollFunction()};
      
      function scrollFunction() {
        if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
          //mybutton.style.display = "block";
        } else {
          //mybutton.style.display = "none";
        }
      }
      
      // When the user clicks on the button, scroll to the top of the document
      function topFunction() {
        document.body.scrollTop = 0;
        document.documentElement.scrollTop = 0;
      }

      $(document).click(function(e) {
         
        if(e.target.className == "off-canvas-overlay") {
          $("body").removeClass("body-hidden");
        } 
         
        
      });
      $(".btn-close-off-canvas").on("click",function() {
        $("body").removeClass("body-hidden");
      });

      
      
      $(document).on("click", ".loginBtn", function() {
       
        
        $('body').removeClass('body-hidden');
        
      });
      $(".registerBtn").on("click",function() {
        $("body").removeClass("body-hidden");
      });

      // Off Canvas Open close
      $(".mobile-menu-btn").on('click', function () {
        $("body").addClass('fix');
        $(".off-canvas-wrapper").addClass('open');
      });

      $(".btn-close-off-canvas,.off-canvas-overlay").on('click', function () {
        $("body").removeClass('fix');
        $(".off-canvas-wrapper").removeClass('open');
      });

	// offcanvas mobile menu
    var $offCanvasNav = $('.mobile-menu'),
        $offCanvasNavSubMenu = $offCanvasNav.find('.dropdown');
    
    /*Add Toggle Button With Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.parent().prepend('<span class="menu-expand"><i></i></span>');
    
    /*Close Off Canvas Sub Menu*/
    $offCanvasNavSubMenu.slideUp();

    /*Category Sub Menu Toggle*/
    $offCanvasNav.on('click', 'li a, li .menu-expand', function(e) {
        var $this = $(this);
        if ( ($this.parent().attr('class').match(/\b(menu-item-has-children|has-children|has-sub-menu)\b/)) && ($this.attr('href') === '#' || $this.hasClass('menu-expand')) ) {
            e.preventDefault();
            if ($this.siblings('ul:visible').length){
                $this.parent('li').removeClass('active');
                $this.siblings('ul').slideUp();
            } else {
                $this.parent('li').addClass('active');
                $this.closest('li').siblings('li').removeClass('active').find('li').removeClass('active');
                $this.closest('li').siblings('li').find('ul:visible').slideUp();
                $this.siblings('ul').slideDown();
            }
        }
    });
    </script>


 