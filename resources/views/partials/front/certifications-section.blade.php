@if(!empty($certifications))
<div class="section certification" data-aos="fade-up" data-aos-once="true">
    <div class="container">
      <div class="heading-sec">
        <h2>{{ isset($certifications->title) ? $certifications->title : '' }}</h2>
        <img src="{{ asset('img/home_line.png') }}" alt=""/>
      </div>
      <ul>
        <li><img src="{{ asset('img/certification-logo.png') }}" alt=""/></li>
        <li><img src="{{ asset('img/GIA-logo.jpg') }}" alt=""/></li>
      </ul>
       
      {!! isset($certifications->content) ? $certifications->content : '' !!}
    </div>
  </div>
@endif
  